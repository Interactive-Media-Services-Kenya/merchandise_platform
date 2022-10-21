<?php

namespace App\Http\Controllers;

use App\Mail\AssignMerchandise;
use App\Models\Activity;
use App\Models\Batch;
use App\Models\BatchBrandambassador;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Client;
use App\Models\Color;
use App\Models\Customer;
use App\Models\IssueProduct;
use App\Models\Outlet;
use App\Models\Product;
use App\Models\Productbas;
use App\Models\ProductCode;
use App\Models\Reject;
use App\Models\Size;
use App\Models\Storage;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Exception;
use Gate;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate as FacadesGate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Picqer\Barcode\BarcodeGeneratorHTML;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Services\SendSMSService;
use App\Services\PermissionsService;
use Adrianorosa\GeoLocation\GeoLocation;
use App\Services\GetLocationDistance;

class ProductsController extends Controller
{
    protected $permissionsService;

    protected $sendSMSService;
    protected $getLocationDistance;

    public function __construct(SendSMSService $sendSMSService,PermissionsService $permissionsService,GetLocationDistance $getLocationDistance)
    {
        $this->sendSMSService = $sendSMSService;
        $this->permissionsService = $permissionsService;
        $this->getLocationDistance = $getLocationDistance;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $rejects = Reject::select('product_id')->get();
        $productsAdmin = Product::with(['category', 'assign', 'batch', 'client'])->select('products.*')->count();
        // dd($productsAdmin);
        $products = Product::where('owner_id', Auth::id())->count();
        $productsClient = count(Product::where('client_id', Auth::user()->client_id)->cursor());
        $productsIssuedOut = count(Productbas::all());
        $productsIssuedOutTL = count(Productbas::join('batches', 'batches.id', 'productbas.batch_id')
            ->where('batches.tl_id_accept', Auth::id())->cursor());
        $batches = Batch::all();
        $brandAmbassadors = User::where('role_id', 4)->where('teamleader_id', Auth::id())->cursor();
        $clients = Client::all();
        $clientsWithMerchandiseTL = Product::select('client_id')->where('assigned_to', Auth::id())->groupBy('client_id')->get();
        $clientsWithMerchandise = Product::select('client_id')->groupBy('client_id')->get();
        $batchesAccepted = Batch::where('accept_status', 1)->get();
        $teamleaders = User::where('role_id', 3)->get();

        $teamleadersWithBatches = Batch::where('accept_status', 1)->groupBy('tl_id_accept')->get();
        //dd($batchesAccepted);
        // ! Products Belonging to a Team Leader
        $productsTls = Product::select('products.*')->where('products.assigned_to', Auth::id())
            ->join('batches', 'batches.id', 'products.batch_id')
            ->where('batches.tl_id_accept', Auth::id())->count();
        // dd($productsTls);
        $issuedProducts = IssueProduct::select('product_id')->where('ba_id', Auth::id())->get();
        $productsBa = Productbas::select('product_id')->where('assigned_to', Auth::id())->get();
        // ! Filter Confirmed Product (accept_status) belonging to Auth Brand Ambassador and not issued out
        $productsBas = Product::with(['category', 'assign', 'batch', 'client'])->where('products.accept_status', 1)->whereIn('products.id', $productsBa)->whereNotIn('products.id', $issuedProducts)->select('products.*')->get();
        $productsBasBatch = Product::with(['category', 'assign', 'batch', 'client'])->where('products.accept_status', 1)->whereIn('products.id', $productsBa)->whereNotIn('products.id', $issuedProducts)->select('products.batch_id')->cursor();
        $batchesTl = Product::select('*')->where('assigned_to', Auth::id())->groupBy('batch_id')->get();


        $batchesBa = Batch::select('*')->whereIn('batches.id', $productsBasBatch)->get();

                //Client Data


        $productsClientIds = Product::select('id')->where('client_id',Auth::user()->client_id)->cursor();
        $productsIssuedOutClient = IssueProduct::whereIn('product_id',$productsClientIds)->count();
        $salesreps = User::where('role_id', 3)->where('client_id', Auth::user()->client_id)->count();
        //Ajax Datatables for products

        if ($request->ajax()) {
            if (Auth::user()->role_id == 1) {
                $query = Product::with(['category', 'assign', 'batch', 'client'])->select('products.*')->where('product_code', '!=', null);
            } elseif (Auth::user()->role_id == 2) {
                $query = Product::with(['category', 'assign', 'batch', 'client'])->where('products.owner_id', Auth::id())->select('products.*')->where('product_code', '!=', null);
            } elseif (Auth::user()->role_id == 3) {
                $query = Product::with(['category', 'assign', 'batch', 'client'])->where('products.assigned_to', Auth::id())
                    ->join('batch_teamleaders', 'batch_teamleaders.id', 'products.batch_tl_id')
                    ->where('batch_teamleaders.accept_status', 1)->select('products.*');
            } elseif (Auth::user()->role_id == 5) {
                $query = Product::with(['category', 'assign', 'batch', 'client'])->where('client_id', Auth::user()->client_id)->select('products.*');
            } elseif (Auth::user()->role_id == 4) {
                $issuedProducts = IssueProduct::select('product_id')->cursor();
                $query = Product::with(['category', 'assign', 'batch', 'client'])->where('products.ba_id', Auth::id())
                    ->join('batch_brandambassadors', 'batch_brandambassadors.id', 'products.batch_ba_id')
                    ->whereOr('batch_brandambassadors.accept_status', 1)->whereNotIn('products.id', $issuedProducts)->select('products.*');
            } else {

                $query = Product::with(['category', 'assign', 'batch', 'client'])->where('products.accept_status', 1)->whereIn('products.id', $productsBa)
                                ->whereNotIn('products.id', $issuedProducts)->select('products.*');
            }

            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('action', 'action');
            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('product_code', function ($row) {
                return $row->product_code ? $row->product_code : 'Not Assigned';
            });
            $table->editColumn('category', function ($row) {
                return $row->category_id ? $row->category->title : '';
            });
            $table->editColumn('client', function ($row) {
                return $row->client_id ? $row->client->name : '';
            });
            $table->editColumn('assign', function ($row) {
                return $row->assigned_to ? $row->assign->email : '';
            });
            $table->editColumn('batch', function ($row) {

                //Super Admin |Agency| Client
                if (Auth::user()->role_id == 1 ||Auth::user()->role_id == 2||Auth::user()->role_id == 5) {
                    return $row->batch_id ? $row->batch->batch_code : '';
                }
                //Teamleader
                if (Auth::user()->role_id == 3) {
                    return $row->batch_id ? DB::table('batch_teamleaders')->whereid($row->batch_tl_id)->value('batch_code') : '';
                }
                //BrandAmbassador
                if (Auth::user()->role_id == 4) {
                    return $row->batch_id ? DB::table('batch_brandambassadors')->whereid($row->batch_ba_id)->value('batch_code') : '';
                }
            });

            $table->editColumn('action', function ($row) {
                if (Auth::user()->role_id == 1) {
                    return '<a href="products/' . $row->id . '/edit"
                                class="btn btn-primary btn-sm">Edit</a>';
                } elseif (Auth::user()->role_id == 4) {
                    return '<a href="products/issue/product/' . $row->id . '/' . $row->batch_ba_id . '"
                   class="btn btn-sm btn-warning">Issue Out</a>';
                } else {
                    return "No Action";
                }
            });

            $table->editColumn('bar_code', function ($row) {
                if ($row->product_code != null) {
                    $generator = new BarcodeGeneratorHTML();
                    return $generator->getBarcode($row->product_code, $generator::TYPE_CODE_128);
                } else {
                    return "No Product Code";
                }
            });

            $table->rawColumns(['placeholder', 'id', 'product_code', 'category', 'client', 'assign', 'batch', 'bar_code', 'action']);

            return $table->make(true);
        }
        return view('products.index', compact(
            'products',
            'productsClient',
            'batchesTl',
            'productsTls',
            'productsBas',
            'batchesBa',
            'teamleaders',
            'salesreps',
            'teamleadersWithBatches',
            'clientsWithMerchandiseTL',
            'brandAmbassadors',
            'productsIssuedOutTL',
            'batches',
            'batchesAccepted',
            'clients',
            'clientsWithMerchandise',
            'productsIssuedOut',
            'productsAdmin',
            'productsIssuedOutClient'
        ));
    }
    public function indexAgency(Request $request,$id)
    {

        $issuedProducts = IssueProduct::select('product_id')->where('ba_id', Auth::id())->get();

        //Ajax Datatables for products

        if ($request->ajax()) {
            if (Auth::user()->role_id == 5) {
                $query = Product::with(['category', 'assign', 'batch', 'client','issueProduct'])->whereowner_id($id)->where('client_id', Auth::user()->client_id)->select('products.*');
            }else {

                $query = Product::with(['category', 'assign', 'batch', 'client'])->where('products.accept_status', 1)
                    ->whereNotIn('products.id', $issuedProducts)->select('products.*');
            }
            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('action', 'action');
            $table->addColumn('issued_at', 'issued_at');
            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('product_code', function ($row) {
                return $row->product_code ? $row->product_code : 'Not Assigned';
            });
            $table->editColumn('category', function ($row) {
                return $row->category_id ? $row->category->title : '';
            });
            $table->editColumn('client', function ($row) {
                return $row->client_id ? $row->client->name : '';
            });
            $table->editColumn('assign', function ($row) {
                return $row->assigned_to ? $row->assign->email : '';
            });
            $table->editColumn('issued_at', function ($row) {
                return $row->issueProduct->created_at ?? '';
            });
            $table->editColumn('created_at', function ($row) {
                return $row->client_id ? $row->created_at: '';
            });
            $table->editColumn('batch', function ($row) {

                //Super Admin
                if (Auth::user()->role_id == 1) {
                    return $row->batch_id ? $row->batch->batch_code : '';
                }
                //Agency
                if (Auth::user()->role_id == 2) {
                    return $row->batch_id ? $row->batch->batch_code : '';
                }
                //Teamleader
                if (Auth::user()->role_id == 3) {
                    return $row->batch_id ? DB::table('batch_teamleaders')->whereid($row->batch_tl_id)->value('batch_code') : '';
                }
                //BrandAmbassador
                if (Auth::user()->role_id == 4) {
                    return $row->batch_id ? DB::table('batch_brandambassadors')->whereid($row->batch_ba_id)->value('batch_code') : '';
                }
                // Client
                if (Auth::user()->role_id == 5) {
                    return $row->batch_id ? $row->batch->batch_code : '';
                }
            });

            $table->editColumn('action', function ($row) {
                if (Auth::user()->role_id == 1) {
                    return '<a href="products/' . $row->id . '/edit"
                                class="btn btn-primary btn-sm">Edit</a>';
                } elseif (Auth::user()->role_id == 4) {
                    return '<a href="products/issue/product/' . $row->id . '/' . $row->batch_ba_id . '"
                   class="btn btn-sm btn-warning">Issue Out</a>';
                } else {
                    return "No Action";
                }
            });

            $table->editColumn('bar_code', function ($row) {
                if ($row->product_code != null) {
                    $generator = new BarcodeGeneratorHTML();
                    return $generator->getBarcode($row->product_code, $generator::TYPE_CODE_128);
                } else {
                    return "No Product Code";
                }
            });

            $table->rawColumns(['placeholder', 'id', 'product_code', 'category', 'client', 'assign', 'batch', 'bar_code', 'action']);

            return $table->make(true);
        }
        return view('agencies.show');
    }

    public function assignProductsCreate()
    {
        $teamleaders = User::where('role_id', 3)->where('client_id', null)->get();
        $salesreps = User::where('role_id', 3)->where('client_id', Auth::user()->client_id)->get();
        $agencies = User::whererole_id(2)->cursor();
        $clients = Client::all();
        $categories = Category::all();
        $colors = Color::all();
        $sizes = Size::all();
        $storages = Storage::all();
        $user_id = Auth::id();
        $brands = Brand::with('client')->get();
        $brandAmbassadors = User::where('role_id', 4)->where('teamleader_id', $user_id)->get();
        $batches = Product::select('batch_id', 'batch_code')->where('assigned_to', Auth::id())->join('batches', 'batches.id', 'products.batch_id')->groupBy('batch_id')->get();
        //dd($batches);

        $batchesAll = Batch::select('batches.*')->join('storages', 'batches.storage_id', 'storages.id')->where('storages.client_id', null)->get();
        $batchesClient = Batch::select('batches.*')->join('storages', 'batches.storage_id', 'storages.id')->where('storages.client_id', Auth::user()->client_id)->get();

        return view('products.assignproducts', compact(
            'teamleaders',
            'salesreps',
            'categories',
            'clients',
            'batchesAll',
            'batchesClient',
            'brandAmbassadors',
            'batches',
            'storages',
            'brands',
            'sizes',
            'colors',
            'agencies',
        ));
    }

    public function assignProductsCreateBA()
    {
        $teamleaders = User::where('role_id', 3)->where('client_id', null)->get();
        $salesreps = User::where('role_id', 3)->where('client_id', Auth::user()->client_id)->get();
        $agencies = User::whererole_id(2)->cursor();
        $clients = Client::all();
        $categories = Category::all();
        $colors = Color::all();
        $sizes = Size::all();
        $storages = Storage::all();
        $user_id = Auth::id();
        $brands = Brand::with('client')->get();
        $brandambassadors = User::where('role_id', 4)->get();
        $brandAmbassadors = User::where('role_id', 4)->where('teamleader_id', $user_id)->get();
        $batches = Product::select('batch_id', 'batch_code')->where('assigned_to', Auth::id())->join('batches', 'batches.id', 'products.batch_id')->groupBy('batch_id')->get();
        //Get Batches for a specific Team Leader
        $batchTLs = Product::select('products.batch_tl_id', 'batch_teamleaders.batch_code')->where('products.assigned_to', Auth::id())->join('batch_teamleaders', 'batch_teamleaders.id', 'products.batch_tl_id')->groupBy('batch_tl_id')->get();


        $batchesAll = Batch::select('batches.*')->join('storages', 'batches.storage_id', 'storages.id')->where('storages.client_id', null)->get();
        $batchesClient = Batch::select('batches.*')->join('storages', 'batches.storage_id', 'storages.id')->where('storages.client_id', Auth::user()->client_id)->get();

        return view('products.assignproductba', compact(
            'teamleaders',
            'salesreps',
            'categories',
            'clients',
            'batchesAll',
            'batchesClient',
            'batchTLs',
            'brandAmbassadors',
            'brandambassadors',
            'batches',
            'storages',
            'brands',
            'sizes',
            'colors',
            'agencies',
        ));
    }

    public function assignProductsCreateTL()
    {
        $teamleaders = User::where('role_id', 3)->get();
        $salesreps = User::where('role_id', 3)->where('client_id', Auth::user()->client_id)->whereNotNull('client_id')->get();
        $agencies = User::whererole_id(2)->cursor();
        $clients = Client::all();
        $categories = Category::all();
        $colors = Color::all();
        $sizes = Size::all();
        $storages = Storage::all();
        $user_id = Auth::id();
        $brands = Brand::with('client')->get();
        $brandAmbassadors = User::where('role_id', 4)->where('teamleader_id', $user_id)->get();

        $batches = Product::select('batch_id', 'batch_code')->where('assigned_to', Auth::id())->join('batches', 'batches.id', 'products.batch_id')->groupBy('batch_id')->get();
        //dd($batches);

        $batchesAll = Batch::select('batches.*')->join('storages', 'batches.storage_id', 'storages.id')->where('storages.client_id', null)->get();
        $batchesClient = Batch::select('batches.*')->join('storages', 'batches.storage_id', 'storages.id')->where('storages.client_id', Auth::user()->client_id)->get();

        return view('products.assignproducttl', compact(
            'teamleaders',
            'salesreps',
            'categories',
            'clients',
            'batchesAll',
            'batchesClient',
            'brandAmbassadors',
            'batches',
            'storages',
            'brands',
            'sizes',
            'colors',
            'agencies',
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        $productCodeAssigned = Product::where('product_code', $request->product_code)->first();
        if ($productCodeAssigned) {
            return response()->json([
                'status' => 503,
                'message' => 'Merchandise Code Already Assigned'
            ]);
        }
        $productCodeNotFound = ProductCode::where('product_code', $request->product_code)->first();

        if ($productCodeNotFound == null) {
            return response()->json([
                'status' => 504,
                'message' => 'Merchandise Code is Invalid'
            ]);
        }
        $data = Product::create([
            'product_code' => $request->product_code,
            'user_id' => Auth::id(),
            // 'owner_id' => $request->owner_id,
            'category_id' => $request->category_id,
            'client_id' => $request->client_id,
            'brand_id' => $request->brand_id,
            'size' => $request->size,
            'color' => $request->color,
        ]);
        Activity::create([
            'title' => 'Merchandise Created',
            'user_id' => Auth::id(),
            'description' => Auth::user()->name . ' Added Merchandise:' . $request->product_code,
        ]);
        if ($data) {
            return response()->json([
                'status' => 200,
                'message' => 'Merchandise Code Uploaded'
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'An Error Occurred'
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teamleaders = User::where('role_id', 3)->get();
        $clients = Client::all();
        $categories = Category::where('client_id', null)->get();
        $categoriesClient = Category::where('client_id', Auth::user()->client_id)->get();
        $colors = Color::all();
        $sizes = Size::all();

        $brands = Brand::all();
        $brandsClient = Brand::where('client_id', Auth::user()->client_id)->get();
        $storages = Storage::get();
        $storagesClient = Storage::where('client_id', Auth::user()->client_id)->get();
        $user_id = Auth::id();


        $brandAmbassadors = User::where('role_id', 4)->where('teamleader_id', $user_id)->get();
        //
        $batches = Product::select('batch_id', 'batch_code')->where('assigned_to', Auth::id())->join('batches', 'batches.id', 'products.batch_id')->groupBy('batch_id')->get();
        $batchesTL = Product::select('batch_id', 'batch_code')->where('assigned_to', Auth::id())->join('batch_teamleaders', 'batch_teamleaders.id', 'products.batch_tl_id')->groupBy('batch_id')->get();

        return view('products.create', compact(
            'teamleaders',
            'categories',
            'categoriesClient',
            'clients',
            'brandAmbassadors',
            'batches',
            'batchesTL',
            'storages',
            'storagesClient',
            'brandsClient',
            'brands',
            'sizes',
            'colors'
        ));
    }

    // Assign Merchandise to BrandAmbassadors
    public function storeBA(Request $request)
    {
        $request->validate([
            'category_id' => 'integer',
            'client_id' => 'integer',
            'ba_id' => 'required|integer',
            'brand_id' => 'integer',
            'size' => 'integer',
            'color' => 'integer',
            'quantity' => 'integer',
        ]);
        if (FacadesGate::allows('admin_access')) {
            //Store Products on count Not Assigned to any brandAmbassador.
            $url_login = URL::to('/login');
            //if quantity is  0ne
            if ($request->quantity == 1) {
                //when Both size & brand is set
                if ($request->size != null && $request->brand_id != null) {
                    $product = Product::where('category_id', $request->category_id)
                        ->where('client_id', $request->client_id)
                        ->where('brand_id', $request->brand_id)
                        ->where('category_id', $request->category_id)
                        ->where('size', $request->size)
                        ->where('color', $request->color)->whereba_id(null)->whereassigned_to(null)->whereowner_id(0)->first();
                    if ($product == null) {
                        Alert::error('Failed', 'No Merchandise Found! Kindly Add the Merchandise Before Assigning');
                        return back();
                    }
                    $product->update([
                        'ba_id' => $request->ba_id,
                        'campaign_id' => $request->campaign_id,
                    ]);
                }
                if ($request->size == null) {
                    $product = Product::where('category_id', $request->category_id)
                        ->where('client_id', $request->client_id)
                        ->where('brand_id', $request->brand_id)
                        ->where('category_id', $request->category_id)
                        ->where('color', $request->color)->whereba_id(null)->whereassigned_to(null)->whereowner_id(0)->first();
                    if ($product == null) {
                        Alert::error('Failed', 'No Merchandise Found with no size! Kindly Add the Merchandise Before Assigning');
                        return back();
                    }
                    $product->update([
                        'ba_id' => $request->ba_id,
                        'campaign_id' => $request->campaign_id,
                    ]);
                }

                if ($request->brand_id == null) {
                    $product = Product::where('category_id', $request->category_id)
                        ->where('client_id', $request->client_id)
                        ->where('category_id', $request->category_id)
                        ->where('size', $request->size)
                        ->where('color', $request->color)->whereba_id(null)->whereassigned_to(null)->whereowner_id(0)->first();
                    if ($product == null) {
                        Alert::error('Failed', 'No Merchandise Found with no brand! Kindly Add the Merchandise Before Assigning');
                        return back();
                    }
                    $product->update([
                        'ba_id' => $request->ba_id,
                        'campaign_id' => $request->campaign_id,
                    ]);
                }
                //! Sending to the Assignee (Assignee)
                $assigneePhone = Auth::user()->phone;
                $assigneeMessage = 'Merchandise ' . $product->product_code . ' assigned to ' . User::whereid($request->ba_id)->value('name') . ' Phone: ' . User::whereid($request->ba_id)->value('phone');
                $this->sendSMS($assigneePhone, $assigneeMessage);

                //! Sending to the Assigned User (Brand Ambassador)
                $assignedPhone = User::whereid($request->ba_id)->value('phone');
                $assignedMessage = 'You have been assigned Merchandise: ' . $product->product_code . ' by ' . Auth::user()->name. ' Kindly Login to the App by clicking the link : ' . $url_login;
                $this->sendSMS($assignedPhone, $assignedMessage);
                Alert::success('Success', 'Operation Successful');
                return back();
            }
            // Quantity is greater than one
            if ($request->quantity > 1) {

                $productCount = Product::where('category_id', $request->category_id)
                    ->where('client_id', $request->client_id)
                    ->where('brand_id', $request->brand_id)
                    ->where('size', $request->size)
                    ->where('color', $request->color)->whereba_id(null)->whereassigned_to(null)->whereowner_id(0)->count();
                    //dd($productCount);

                if ($request->quantity > $productCount) {
                    Alert::error('Failed', 'Quantity Exceeds Expected Amount. Remaining: ' . $productCount);
                    return back();
                }
                //Create Batch for the product Group
                $batch_code = $this->generateBatchCode() . '-BA-' . $request->ba_id;
                $batch = DB::table('batch_brandambassadors')->insert([
                    'brand_ambassador_id' => $request->ba_id,
                    'batch_code' => $batch_code,
                    'created_at' => Carbon::now(),
                ]);

                for ($i = 0; $i < $request->quantity; $i++) {
                    //when Both size & brand is set
                    if ($request->size != null && $request->brand_id != null) {
                        $product = Product::where('category_id', $request->category_id)
                            ->where('client_id', $request->client_id)
                            ->where('brand_id', $request->brand_id)
                            ->where('category_id', $request->category_id)
                            ->where('size', $request->size)
                            ->where('color', $request->color)->whereba_id(null)->whereassigned_to(null)->whereowner_id(0)->first();

                        if ($product == null) {
                            Alert::error('Failed', 'No Merchandise Found! Kindly Add the Merchandise Before Assigning');
                            return back();
                        }

                        $product->update([
                            'ba_id' => $request->ba_id,
                            'campaign_id' => $request->campaign_id,
                            'batch_ba_id' => DB::table('batch_brandambassadors')->where('batch_code', $batch_code)->value('id'),
                        ]);
                    }
                    if ($request->size == null) {
                        $product = Product::where('category_id', $request->category_id)
                            ->where('client_id', $request->client_id)
                            ->where('brand_id', $request->brand_id)
                            ->where('category_id', $request->category_id)
                            ->where('color', $request->color)->whereba_id(null)->whereassigned_to(null)->whereowner_id(0)->first();

                        if ($product == null) {
                            Alert::error('Failed', 'No Merchandise Found! Kindly Add the Merchandise Before Assigning');
                            return back();
                        }

                        $product->update([
                            'assigned_to' => $request->team_leader_id,
                            'batch_tl_id' =>  DB::table('batch_brandambassadors')->where('batch_code', $batch_code)->value('id'),
                        ]);
                    }

                    if ($request->brand_id == null) {
                        $product = Product::where('category_id', $request->category_id)
                            ->where('client_id', $request->client_id)
                            ->where('category_id', $request->category_id)
                            ->where('size', $request->size)
                            ->where('color', $request->color)->whereba_id(null)->whereassigned_to(null)->whereowner_id(0)->first();
                        if ($product == null) {
                            Alert::error('Failed', 'No Merchandise Found! Kindly Add the Merchandise Before Assigning');
                            return back();
                        }

                        $product->update([
                            'assigned_to' => $request->team_leader_id,
                            'batch_tl_id' =>  DB::table('batch_brandambassadors')->where('batch_code', $batch_code)->value('id'),
                            'campaign_id' => $request->campaign_id,
                        ]);
                    }
                }
                //! Sending SMS to the Assignee
                $assigneePhone = Auth::user()->phone;
                $assigneeMessage = 'Batch: ' . $batch_code . ' of ' . $request->quantity . ' ' . DB::table('categories')->whereid($request->category_id)->value('title') . ' assigned to ' . User::whereid($request->ba_id)->value('name') . ' Phone: ' . User::whereid($request->ba_id)->value('phone');
                $this->sendSMS($assigneePhone, $assigneeMessage);

                //! Sending SMS to the Assigned User (Brand Ambassador)
                $assignedPhone = User::whereid($request->ba_id)->value('phone');
                $assignedMessage = 'You have been assigned Merchandise (' . DB::table('categories')->whereid($request->category_id)->value('title') . ') Batch Code: ' . $batch_code . ' by ' . Auth::user()->name . '. Kindly Login to the App by clicking the link : ' . $url_login;
                $this->sendSMS($assignedPhone, $assignedMessage);
                Alert::success('Success', 'Operation Successful');
                return back();
            } else {
                Alert::error('Failed', 'Invalid Merchandise Quantity');
                return back();
            }
        }
        // ? Agency Assigns to Team Leader
        if (FacadesGate::allows('tb_access')) {
            //Store Products on count Not Assigned to any Agency.
            $url_login = URL::to('/login');
            //if quantity is  0ne
            if ($request->quantity == 1) {
                //when Both size & brand is set
                if ($request->size != null && $request->brand_id != null) {
                    $product = Product::where('category_id', $request->category_id)
                        ->where('client_id', $request->client_id)
                        ->where('brand_id', $request->brand_id)
                        ->where('category_id', $request->category_id)
                        ->where('size', $request->size)
                        ->where('color', $request->color)->whereowner_id(Auth::id())->whereba_id(null)->first();
                    // dd($product);
                    if ($product == null) {
                        Alert::error('Failed', 'No Merchandise Found! Kindly Add the Merchandise Before Assigning');
                        return back();
                    }
                    $product->update([
                        'ba_id' => $request->ba_id??'',
                        'campaign_id' => $request->campaign_id??'',
                    ]);
                }
                if ($request->size == null) {
                    $product = Product::where('category_id', $request->category_id)
                        ->where('client_id', $request->client_id)
                        ->where('brand_id', $request->brand_id)
                        ->where('category_id', $request->category_id)
                        ->where('color', $request->color)->whereowner_id(Auth::id())->whereba_id(null)->first();
                    if ($product == null) {
                        Alert::error('Failed', 'No Merchandise Found with no size! Kindly Add the Merchandise Before Assigning');
                        return back();
                    }
                    $product->update([
                        'ba_id' => $request->ba_id,
                        'campaign_id' => $request->campaign_id,
                    ]);
                }

                if ($request->brand_id == null) {
                    $product = Product::where('category_id', $request->category_id)
                        ->where('client_id', $request->client_id)
                        ->where('category_id', $request->category_id)
                        ->where('size', $request->size)
                        ->where('color', $request->color)->whereowner_id(Auth::id())->whereba_id(null)->first();
                    if ($product == null) {
                        Alert::error('Failed', 'No Merchandise Found with no brand! Kindly Add the Merchandise Before Assigning');
                        return back();
                    }
                    $product->update([
                        'ba_id' => $request->ba_id,
                        'campaign_id' => $request->campaign_id,
                    ]);
                }
                //! Sending to the Assignee (Super Admin)
                $assigneePhone = Auth::user()->phone;
                $assigneeMessage = 'Merchandise ' . $product->product_code . ' assigned to ' . User::whereid($request->ba_id)->value('name') . ' Phone: ' . User::whereid($request->ba_id)->value('phone');
                $this->sendSMS($assigneePhone, $assigneeMessage);

                //! Sending to the Assigned User (Agency)
                $assignedPhone = User::whereid($request->ba_id)->value('phone');
                $assignedMessage = 'You have been assigned Merchandise (' . DB::table('categories')->whereid($request->category_id)->value('title') . '): ' . $product->product_code . ' by ' . Auth::user()->name . ' Kindly Login to the App by clicking the link : ' . $url_login;
                $this->sendSMS($assignedPhone, $assignedMessage);
                Alert::success('Success', 'Operation Successful');
                return back();
            }

            if ($request->quantity > 1) {
                //Create Batch for the product Group
                $productCount = Product::where('category_id', $request->category_id)
                    ->where('client_id', $request->client_id)
                    ->where('brand_id', $request->brand_id)
                    ->where('size', $request->size)
                    ->where('color', $request->color)->whereowner_id(Auth::id())->whereba_id(null)->count();

                if ($productCount < $request->quantity) {
                    Alert::error('Failed', 'Quantity Exceeds Expected Amount. Remaining: ' . $productCount);
                    return back();
                }
                $batch_code = $this->generateBatchCode() . '-BA-' . $request->ba_id;
                $batch = DB::table('batch_brandambassadors')->insert([
                    'brand_ambassador_id' => $request->ba_id,
                    'batch_code' => $batch_code,
                    'created_at' => Carbon::now(),
                ]);

                for ($i = 0; $i < $request->quantity; $i++) {
                    //when Both size & brand is set
                    if ($request->size != null && $request->brand_id != null) {
                        $product = Product::where('category_id', $request->category_id)
                            ->where('client_id', $request->client_id)
                            ->where('brand_id', $request->brand_id)
                            ->where('category_id', $request->category_id)
                            ->where('size', $request->size)
                            ->where('color', $request->color)->whereowner_id(Auth::id())->whereba_id(null)->first();

                        if ($product == null) {
                            Alert::error('Failed', 'No Merchandise Found! Kindly Add the Merchandise Before Assigning');
                            return back();
                        }

                        $product->update([
                            'ba_id' => $request->ba_id,
                            'batch_ba_id' => DB::table('batch_brandambassadors')->where('batch_code', $batch_code)->value('id'),
                            'campaign_id' => $request->campaign_id,
                        ]);
                    }
                    if ($request->size == null) {
                        $product = Product::where('category_id', $request->category_id)
                            ->where('client_id', $request->client_id)
                            ->where('brand_id', $request->brand_id)
                            ->where('category_id', $request->category_id)
                            ->where('color', $request->color)->whereowner_id(Auth::id())->whereba_id(null)->first();

                        if ($product == null) {
                            Alert::error('Failed', 'No Merchandise Found! Kindly Add the Merchandise Before Assigning');
                            return back();
                        }

                        $product->update([
                            'assigned_to' => $request->team_leader_id,
                            'batch_tl_id' =>  DB::table('batch_brandambassadors')->where('batch_code', $batch_code)->value('id'),
                            'campaign_id' => $request->campaign_id,
                        ]);
                    }

                    if ($request->brand_id == null) {
                        $product = Product::where('category_id', $request->category_id)
                            ->where('client_id', $request->client_id)
                            ->where('category_id', $request->category_id)
                            ->where('size', $request->size)
                            ->where('color', $request->color)->whereowner_id(Auth::id())->whereba_id(null)->first();
                        if ($product == null) {
                            Alert::error('Failed', 'No Merchandise Found! Kindly Add the Merchandise Before Assigning');
                            return back();
                        }

                        $product->update([
                            'assigned_to' => $request->team_leader_id,
                            'batch_tl_id' =>  DB::table('batch_brandambassadors')->where('batch_code', $batch_code)->value('id'),
                            'campaign_id' => $request->campaign_id,
                        ]);
                    }
                }
                //! Sending SMS to the Assignee (Agency)
                $assigneePhone = Auth::user()->phone;
                $assigneeMessage = 'Batch: ' . $batch_code . ' of ' . $request->quantity . ' ' . DB::table('categories')->whereid($request->category_id)->value('title') . ' assigned to ' . User::whereid($request->ba_id)->value('name') . ' Phone: ' . User::whereid($request->ba_id)->value('phone');
                $this->sendSMS($assigneePhone, $assigneeMessage);

                //! Sending to the Assigned User (Team Leader)
                $assignedPhone = User::whereid($request->ba_id)->value('phone');
                $assignedMessage = 'You have been assigned Merchandise (' . $request->quantity . ' ' . DB::table('categories')->whereid($request->category_id)->value('title') . ') Batch Code: ' . $batch_code . ' by ' . Auth::user()->name .' Kindly Login to the App by clicking the link : ' . $url_login;
                $this->sendSMS($assignedPhone, $assignedMessage);
                Alert::success('Success', 'Operation Successful');
                return back();
            } else {
                Alert::error('Failed', 'Invalid Merchandise Quantity');
                return back();
            }
        }
        // ? Team Leader Assigns to Brand Ambassador
        if (FacadesGate::allows('team_leader_access')) {
            //Store Products on count Not Assigned to any Agency.
            $url_login = URL::to('/login');
            //if quantity is  0ne
            if ($request->quantity == 1) {
                //when Both size & brand is set
                if ($request->batch_tl_id != null) {
                    $product = Product::where('batch_tl_id', $request->batch_tl_id)
                                        ->whereassigned_to(Auth::id())
                                        ->whereba_id(null)->first();


                    if ($product == null) {
                        Alert::error('Failed', 'No Merchandise Found! Kindly Add the Merchandise Before Assigning');
                        return back();
                    }
                    $product->update([
                        'ba_id' => $request->ba_id,
                        'campaign_id' => $request->campaign_id,
                    ]);
                }

                //! Sending to the Assignee (Super Admin)
                $assigneePhone = Auth::user()->phone;
                $assigneeMessage = 'Merchandise ' . $product->product_code . ' assigned to ' . User::whereid($request->ba_id)->value('name') . ' Phone: ' . User::whereid($request->ba_id)->value('phone');
                $this->sendSMS($assigneePhone, $assigneeMessage);

                //! Sending to the Assigned User (Agency)
                $assignedPhone = User::whereid($request->ba_id)->value('phone');
                $assignedMessage = 'You have been assigned Merchandise (' . DB::table('categories')->whereid($request->category_id)->value('title') . '): ' . $product->product_code . ' by ' . Auth::user()->name . ' Kindly Login to the App by clicking the link : ' . $url_login;
                $this->sendSMS($assignedPhone, $assignedMessage);
                Alert::success('Success', 'Operation Successful');
                return back();
            }

            if ($request->quantity > 1) {
                //Create Batch for the product Group
                $productCount = Product::where('batch_tl_id', $request->batch_tl_id)
                    ->whereassigned_to(Auth::id())
                    ->whereba_id(null)->count();

                if ($productCount < $request->quantity) {
                    Alert::error('Failed', 'Quantity Exceeds Expected Amount. Remaining: ' . $productCount);
                    return back();
                }
                $batch_code = $this->generateBatchCode() . '-BA-' . $request->ba_id;
                $batch = DB::table('batch_brandambassadors')->insert([
                    'brand_ambassador_id' => $request->ba_id,
                    'batch_code' => $batch_code,
                    'created_at' => Carbon::now(),
                ]);

                for ($i = 0; $i < $request->quantity; $i++) {
                    //when Both size & brand is set
                    if ($request->size != null && $request->brand_id != null) {
                        $product =Product::where('batch_tl_id', $request->batch_tl_id)
                            ->whereowner_id(Auth::id())
                            ->whereba_id(null)->first();

                        if ($product == null) {
                            Alert::error('Failed', 'No Merchandise Found! Kindly Add the Merchandise Before Assigning');
                            return back();
                        }

                        $product->update([
                            'ba_id' => $request->ba_id,
                            'batch_ba_id' => DB::table('batch_brandambassadors')->where('batch_code', $batch_code)->value('id'),
                            'campaign_id' => $request->campaign_id,
                        ]);
                    }
                }
                //! Sending SMS to the Assignee (Agency)
                $assigneePhone = Auth::user()->phone;
                $assigneeMessage = 'Batch: ' . $batch_code . ' of ' . $request->quantity . ' ' . DB::table('categories')->whereid($request->category_id)->value('title') . ' assigned to ' . User::whereid($request->ba_id)->value('name') . ' Phone: ' . User::whereid($request->ba_id)->value('phone');
                $this->sendSMS($assigneePhone, $assigneeMessage);

                //! Sending to the Assigned User (Team Leader)
                $assignedPhone = User::whereid($request->ba_id)->value('phone');
                $assignedMessage = 'You have been assigned Merchandise (' . $request->quantity . ' ' . DB::table('categories')->whereid($request->category_id)->value('title') . ') Batch Code: ' . $batch_code . ' by ' . Auth::user()->name . ' Kindly Login to the App by clicking the link : ' . $url_login;
                $this->sendSMS($assignedPhone, $assignedMessage);
                Alert::success('Success', 'Operation Successful');
                return back();
            } else {
                Alert::error('Failed', 'Invalid Merchandise Quantity');
                return back();
            }
        }
        Alert::error('Failed', 'Unauthorized!');
        return back();
    }
    //Assign Merchandise to Agency
    public function storeAgency(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer',
            'client_id' => 'required|integer',
            'brand_id' => 'integer',
            'size' => 'integer',
            'color' => 'integer',
            'quantity' => 'integer',
        ]);

        $url_login = URL::to('/login');
        //if quantity is  0ne
        if (FacadesGate::allows('admin_access')) {
            if ($request->quantity == 1) {
                //when Both size & brand is set
                if ($request->size != null && $request->brand_id != null) {
                    $product = Product::where('category_id', $request->category_id)
                        ->where('client_id', $request->client_id)
                        ->where('brand_id', $request->brand_id)
                        ->where('category_id', $request->category_id)
                        ->where('size', $request->size)
                        ->where('color', $request->color)->whereowner_id(0)->first();
                    if ($product == null) {
                        Alert::error('Failed', 'No Merchandise Found! Kindly Add the Merchandise Before Assigning');
                        return back();
                    }
                    $product->update([
                        'owner_id' => $request->owner_id,
                    ]);
                }
                if ($request->size == null) {
                    $product = Product::where('category_id', $request->category_id)
                        ->where('client_id', $request->client_id)
                        ->where('brand_id', $request->brand_id)
                        ->where('category_id', $request->category_id)
                        ->where('color', $request->color)->whereowner_id(0)->first();
                    if ($product == null) {
                        Alert::error('Failed', 'No Merchandise Found with no size! Kindly Add the Merchandise Before Assigning');
                        return back();
                    }
                    $product->update([
                        'owner_id' => $request->owner_id,
                    ]);
                }

                if ($request->brand_id == null) {
                    $product = Product::where('category_id', $request->category_id)
                        ->where('client_id', $request->client_id)
                        ->where('category_id', $request->category_id)
                        ->where('size', $request->size)
                        ->where('color', $request->color)->whereowner_id(0)->first();
                    if ($product == null) {
                        Alert::error('Failed', 'No Merchandise Found with no brand! Kindly Add the Merchandise Before Assigning');
                        return back();
                    }
                    $product->update([
                        'owner_id' => $request->owner_id,
                    ]);
                }
                //Send SMS On Assigning Merchandise to Both Parties (Super Admin and The Agency User)

                //! Sending to the Assignee (Super Admin)
                $assigneePhone = Auth::user()->phone;
                $assigneeMessage = 'Merchandise ' . $product->product_code . ' assigned to ' . User::whereid($request->owner_id)->value('name') . ' Phone: ' . User::whereid($request->owner_id)->value('phone');
                $this->sendSMS($assigneePhone, $assigneeMessage);

                //! Sending to the Assigned User (Agency)
                $assignedPhone = User::whereid($request->owner_id)->value('phone');
                $assignedMessage = 'You have been assigned Merchandise: ' . $product->product_code . ' by ' . Auth::user()->name . '. Kindly Login to the App by clicking the link : ' . $url_login;
                $this->sendSMS($assignedPhone, $assignedMessage);

                Alert::success('Success', 'Operation Successful');
                return back();
            }
            $productCount = Product::where('category_id', $request->category_id)
                ->where('client_id', $request->client_id)
                ->where('brand_id', $request->brand_id)
                ->where('category_id', $request->category_id)
                ->where('size', $request->size)
                ->where('color', $request->color)->whereowner_id(0)->count();
            if ($request->quantity > 1 && $request->quantity <= $productCount) {
                //Create Batch for the product Group
                $batch_code = $this->generateBatchCode() . '-AG-' . $request->owner_id;
                $batch = Batch::create([
                    'batch_code' => $batch_code,
                ]);
                for ($i = 0; $i < $request->quantity; $i++) {
                    //when Both size & brand is set
                    if ($request->size != null && $request->brand_id != null) {
                        $product = Product::where('category_id', $request->category_id)
                            ->where('client_id', $request->client_id)
                            ->where('brand_id', $request->brand_id)
                            ->where('category_id', $request->category_id)
                            ->where('size', $request->size)
                            ->where('color', $request->color)->whereowner_id(0)->first();

                        if ($product == null) {
                            Alert::error('Failed', 'No Merchandise Found! Kindly Add the Merchandise Before Assigning');
                            return back();
                        }
                    //    if ($product->count() < $request->quantity) {
                    //        Alert::error('Failed', 'Merchandise Available is less than requested Quantity');
                    //        return back();
                    //    }
                        $product->update([
                            'owner_id' => $request->owner_id,
                            'batch_id' => $batch->id,
                        ]);
                    }
                    if ($request->size == null) {
                        $product = Product::where('category_id', $request->category_id)
                            ->where('client_id', $request->client_id)
                            ->where('brand_id', $request->brand_id)
                            ->where('category_id', $request->category_id)
                            ->where('color', $request->color)->whereowner_id(0)->first();

                        if ($product == null) {
                            Alert::error('Failed', 'No Merchandise Found! Kindly Add the Merchandise Before Assigning');
                            return back();
                        }
                    //    if ($product->count() != $request->quantity) {
                    //        Alert::error('Failed', 'No Merchandise Found with no size! Kindly Add the Merchandise Before Assigning');
                    //        return back();
                    //    }
                        $product->update([
                            'owner_id' => $request->owner_id,
                            'batch_id' => $batch->id,
                        ]);

                    }

                    if ($request->brand_id == null) {
                        $product = Product::where('category_id', $request->category_id)
                            ->where('client_id', $request->client_id)
                            ->where('category_id', $request->category_id)
                            ->where('size', $request->size)
                            ->where('color', $request->color)->whereowner_id(0)->first();
                        if ($product == null) {
                            Alert::error('Failed', 'No Merchandise Found! Kindly Add the Merchandise Before Assigning');
                            return back();
                        }

                        $product->update([
                            'owner_id' => $request->owner_id,
                            'batch_id' => $batch->id,
                        ]);
                    }
                }
                //! Sending to the Assignee (Super Admin)
                $assigneePhone = Auth::user()->phone;
                $assigneeMessage = 'Batch: ' . $batch->batch_code . ' of ' . $request->quantity . ' ' . DB::table('categories')->whereid($request->category_id)->value('title') . ' assigned to ' . User::whereid($request->owner_id)->value('name') . ' Phone: ' . User::whereid($request->owner_id)->value('phone');
                $this->sendSMS($assigneePhone, $assigneeMessage);

                //! Sending to the Assigned User (Agency)
                $assignedPhone = User::whereid($request->owner_id)->value('phone');
                $assignedMessage = 'You have been assigned Merchandise (' . DB::table('categories')->whereid($request->category_id)->value('title') . ') Batch Code: ' . $batch->batch_code . ' by ' . Auth::user()->name .'. Kindly Login to the App by clicking the link : ' . $url_login;
                $this->sendSMS($assignedPhone, $assignedMessage);

                Alert::success('Success', 'Operation Successful');
                return back();
            } else {
                Alert::error('Failed', 'Merchandise Quantity Exceeded. Count Remaining : ' . $productCount);
                return back();
            }
        }
    }
    //Assign Merchandise to TeamLeader
    public function storeTL(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer',
            'client_id' => 'required|integer',
            'team_leader_id' => 'required|integer',
            'brand_id' => 'integer',
            'size' => 'integer',
            'color' => 'integer',
            'quantity' => 'integer',
        ]);
        if (FacadesGate::allows('admin_access')) {
            //Store Products on count Not Assigned to any Agency.
            $url_login = URL::to('/login');
            //if quantity is  0ne
            if ($request->quantity == 1) {
                //when Both size & brand is set
                if ($request->size != null && $request->brand_id != null) {
                    $product = Product::where('category_id', $request->category_id)
                        ->where('client_id', $request->client_id)
                        ->where('brand_id', $request->brand_id)
                        ->where('category_id', $request->category_id)
                        ->where('size', $request->size)
                        ->where('color', $request->color)->whereassigned_to(null)->whereba_id(null)->wherebatch_id(null)->wherebatch_tl_id(null)->first();
                    if ($product == null) {
                        Alert::error('Failed', 'No Merchandise Found! Kindly Add the Merchandise Before Assigning');
                        return back();
                    }
                    $product->update([
                        'assigned_to' => $request->team_leader_id,
                    ]);
                }
                if ($request->size == null) {
                    $product = Product::where('category_id', $request->category_id)
                        ->where('client_id', $request->client_id)
                        ->where('brand_id', $request->brand_id)
                        ->where('category_id', $request->category_id)
                        ->where('color', $request->color)->whereassigned_to(null)->whereba_id(null)->wherebatch_id(null)->wherebatch_tl_id(null)->first();
                    if ($product == null) {
                        Alert::error('Failed', 'No Merchandise Found with no size! Kindly Add the Merchandise Before Assigning');
                        return back();
                    }
                    $product->update([
                        'assigned_to' => $request->team_leader_id,
                    ]);
                }

                if ($request->brand_id == null) {
                    $product = Product::where('category_id', $request->category_id)
                        ->where('client_id', $request->client_id)
                        ->where('size', $request->size)
                        ->where('color', $request->color)->whereassigned_to(null)->whereba_id(null)->wherebatch_id(null)->wherebatch_tl_id(null)->first();
                    if ($product == null) {
                        Alert::error('Failed', 'No Merchandise Found with no brand! Kindly Add the Merchandise Before Assigning');
                        return back();
                    }
                    $product->update([
                        'assigned_to' => $request->team_leader_id,
                    ]);
                }
                //! Sending to the Assignee (Super Admin)
                $assigneePhone = Auth::user()->phone;
                $assigneeMessage = 'Merchandise ' . $product->product_code . ' assigned to ' . User::whereid($request->team_leader_id)->value('name') . ' Phone: ' . User::whereid($request->team_leader_id)->value('phone');
                $this->sendSMS($assigneePhone, $assigneeMessage);

                //! Sending to the Assigned User (Agency)
                $assignedPhone = User::whereid($request->team_leader_id)->value('phone');
                $assignedMessage = 'You have been assigned Merchandise (' . DB::table('categories')->whereid($request->category_id)->value('title') . '): ' . $product->product_code . ' by ' . Auth::user()->name .'. Kindly Login to the App by clicking the link : ' . $url_login;
                $this->sendSMS($assignedPhone, $assignedMessage);
                Alert::success('Success', 'Operation Successful');
                return back();
            }

            if ($request->quantity > 1) {
                //Create Batch for the product Group
                $productCount = Product::where('category_id', $request->category_id)
                    ->where('client_id', $request->client_id)
                    ->where('brand_id', $request->brand_id)
                    ->where('category_id', $request->category_id)
                    ->where('size', $request->size)
                    ->where('color', $request->color)->whereassigned_to(null)->whereowner_id(0)->whereba_id(null)->wherebatch_id(null)->wherebatch_tl_id(null)->count();
                //dd($productCount);
                if ($productCount < $request->quantity) {
                    Alert::error('Failed', 'Quantity Exceeds Expected Amount. Remaining: ' . $productCount);
                    return back();
                }
                //Create Batch Code for the products
                $batch_code = $this->generateBatchCode() . '-TL-' . $request->team_leader_id;
                $batch = DB::table('batch_teamleaders')->insert([
                    'team_leader_id' => $request->team_leader_id,
                    'batch_code' => $batch_code,
                    'created_at' => Carbon::now(),
                ]);

                for ($i = 0; $i <= $request->quantity; $i++) {
                    //when Both size & brand is set
                    if ($request->size != null && $request->brand_id != null) {
                        $product = Product::where('category_id', $request->category_id)
                            ->where('client_id', $request->client_id)
                            ->where('brand_id', $request->brand_id)
                            ->where('category_id', $request->category_id)
                            ->where('size', $request->size)
                            ->where('color', $request->color)->whereassigned_to(null)->whereowner_id(0)->whereba_id(null)->wherebatch_id(null)->wherebatch_tl_id(null)->first();

                        if ($product == null) {
                            Alert::error('Failed', 'No Merchandise Found! Kindly Add the Merchandise Before Assigning');
                            return back();
                        }

                        $product->update([
                            'assigned_to' => $request->team_leader_id,
                            'batch_tl_id' => DB::table('batch_teamleaders')->where('batch_code', $batch_code)->value('id'),
                        ]);
                    }
                    if ($request->size == null) {
                        $product = Product::where('category_id', $request->category_id)
                            ->where('client_id', $request->client_id)
                            ->where('brand_id', $request->brand_id)
                            ->where('category_id', $request->category_id)
                            ->where('color', $request->color)->whereassigned_to(null)->whereowner_id(0)->whereba_id(null)->wherebatch_id(null)->wherebatch_tl_id(null)->first();

                        if ($product == null) {
                            Alert::error('Failed', 'No Merchandise Found! Kindly Add the Merchandise Before Assigning');
                            return back();
                        }

                        $product->update([
                            'assigned_to' => $request->team_leader_id,
                            'batch_tl_id' => $batch->id,
                        ]);
                    }

                    if ($request->brand_id == null) {
                        $product = Product::where('category_id', $request->category_id)
                            ->where('client_id', $request->client_id)
                            ->where('category_id', $request->category_id)
                            ->where('size', $request->size)
                            ->where('color', $request->color)->whereassigned_to(null)->whereba_id(null)->wherebatch_id(null)->wherebatch_tl_id(null)->first();
                        if ($product == null) {
                            Alert::error('Failed', 'No Merchandise Found! Kindly Add the Merchandise Before Assigning');
                            return back();
                        }

                        $product->update([
                            'assigned_to' => $request->team_leader_id,
                            'batch_tl_id' => $batch->id,
                        ]);
                    }
                }

                //! Sending SMS to the Assignee (Super Admin)
                $assigneePhone = Auth::user()->phone;
                $assigneeMessage = 'Batch: ' . $batch_code . ' of ' . $request->quantity . ' ' . DB::table('categories')->whereid($request->category_id)->value('title') . ' assigned to ' . User::whereid($request->team_leader_id)->value('name') . ' Phone: ' . User::whereid($request->team_leader_id)->value('phone');
                $this->sendSMS($assigneePhone, $assigneeMessage);

                //! Sending SMS to the Assigned User (Agency)
                $assignedPhone = User::whereid($request->team_leader_id)->value('phone');
                $assignedMessage = 'You have been assigned Merchandise (' . DB::table('categories')->whereid($request->category_id)->value('title') . ') Batch Code: ' . $batch_code . ' by ' . Auth::user()->name .' Kindly Login to the App by clicking the link : ' . $url_login;
                $this->sendSMS($assignedPhone, $assignedMessage);
                Alert::success('Success', 'Operation Successful');
                return back();
            } else {
                Alert::error('Failed', 'Invalid Merchandise Quantity');
                return back();
            }
        }
        // ? Agency Assigns to Team Leader
        if (FacadesGate::allows('tb_access')) {
            //Store Products on count  Assigned to an Agency.
            $url_login = URL::to('/login');
            //if quantity is  0ne
            if ($request->quantity == 1) {
                //when Both size & brand is set
                if ($request->size != null && $request->brand_id != null) {
                    $product = Product::where('category_id', $request->category_id)
                        ->where('client_id', $request->client_id)
                        ->where('brand_id', $request->brand_id)
                        ->where('category_id', $request->category_id)
                        ->where('size', $request->size)
                        ->where('color', $request->color)->whereassigned_to(null)->whereowner_id(Auth::id())->whereba_id(null)->first();
                    if ($product == null) {
                        Alert::error('Failed', 'No Merchandise Found! Kindly Add the Merchandise Before Assigning');
                        return back();
                    }
                    $product->update([
                        'assigned_to' => $request->team_leader_id,
                        'campaign_id' =>$request->campaign_id, // Attach Campaign Id to product...By Agency
                    ]);
                }
                if ($request->size == null) {
                    $product = Product::where('category_id', $request->category_id)
                        ->where('client_id', $request->client_id)
                        ->where('brand_id', $request->brand_id)
                        ->where('category_id', $request->category_id)
                        ->where('color', $request->color)->whereassigned_to(null)->whereowner_id(Auth::id())->whereba_id(null)->first();
                    if ($product == null) {
                        Alert::error('Failed', 'No Merchandise Found with no size! Kindly Add the Merchandise Before Assigning');
                        return back();
                    }
                    $product->update([
                        'assigned_to' => $request->team_leader_id,
                        'campaign_id' =>$request->campaign_id, // Attach Campaign Id to product...By Agency
                    ]);
                }

                if ($request->brand_id == null) {
                    $product = Product::where('category_id', $request->category_id)
                        ->where('client_id', $request->client_id)
                        ->where('category_id', $request->category_id)
                        ->where('size', $request->size)
                        ->where('color', $request->color)->whereassigned_to(null)->whereowner_id(Auth::id())->whereba_id(null)->first();
                    if ($product == null) {
                        Alert::error('Failed', 'No Merchandise Found with no brand! Kindly Add the Merchandise Before Assigning');
                        return back();
                    }
                    $product->update([
                        'assigned_to' => $request->team_leader_id,
                        'campaign_id' =>$request->campaign_id, // Attach Campaign Id to product...By Agency
                    ]);
                }
                //! Sending to the Assignee (Agency)
                $assigneePhone = Auth::user()->phone;
                $assigneeMessage = 'Merchandise (' . DB::table('categories')->whereid($request->category_id)->value('title') . ') ' . $product->product_code . ' assigned to ' . User::whereid($request->team_leader_id)->value('name') . ' Phone: ' . User::whereid($request->team_leader_id)->value('phone');
                $this->sendSMS($assigneePhone, $assigneeMessage);

                //! Sending to the Assigned User (TeamLeader)
                $assignedPhone = User::whereid($request->team_leader_id)->value('phone');
                $assignedMessage = 'You have been assigned Merchandise (' . DB::table('categories')->whereid($request->category_id)->value('title') . '): ' . $product->product_code . ' by ' . Auth::user()->name . '. Kindly Login to the App by clicking the link : ' . $url_login;
                $this->sendSMS($assignedPhone, $assignedMessage);
                Alert::success('Success', 'Operation Successful');
                return back();
            }

            if ($request->quantity > 1) {
                //Create Batch for the product Group
                $productCount = Product::where('category_id', $request->category_id)
                    ->where('client_id', $request->client_id)
                    ->where('brand_id', $request->brand_id)
                    ->where('category_id', $request->category_id)
                    ->where('size', $request->size)
                    ->where('color', $request->color)->whereassigned_to(null)->whereowner_id(Auth::id())->whereba_id(null)->count();

                if ($productCount < $request->quantity) {
                    Alert::error('Failed', 'Quantity Exceeds Expected Amount. Remaining: ' . $productCount);
                    return back();
                }
                $batch_code = $this->generateBatchCode() . '-TL-' . $request->team_leader_id;
                $batch = DB::table('batch_teamleaders')->insert([
                    'team_leader_id' => $request->team_leader_id,
                    'batch_code' => $batch_code,
                    'created_at' => Carbon::now(),
                ]);

                for ($i = 0; $i < $request->quantity; $i++) {
                    //when Both size & brand is set
                    if ($request->size != null && $request->brand_id != null) {
                        $product = Product::where('category_id', $request->category_id)
                            ->where('client_id', $request->client_id)
                            ->where('brand_id', $request->brand_id)
                            ->where('category_id', $request->category_id)
                            ->where('size', $request->size)
                            ->where('color', $request->color)->whereassigned_to(null)->whereowner_id(Auth::id())->whereba_id(null)->first();

                        if ($product == null) {
                            Alert::error('Failed', 'No Merchandise Found! Kindly Add the Merchandise Before Assigning');
                            return back();
                        }

                        $product->update([
                            'assigned_to' => $request->team_leader_id,
                            'batch_tl_id' => DB::table('batch_teamleaders')->where('batch_code', $batch_code)->value('id'),
                            'campaign_id' =>$request->campaign_id, // Attach Campaign Id to product...By Agency
                        ]);
                    }
                    if ($request->size == null) {
                        $product = Product::where('category_id', $request->category_id)
                            ->where('client_id', $request->client_id)
                            ->where('brand_id', $request->brand_id)
                            ->where('category_id', $request->category_id)
                            ->where('color', $request->color)->whereassigned_to(null)->whereowner_id(Auth::id())->whereba_id(null)->first();

                        if ($product == null) {
                            Alert::error('Failed', 'No Merchandise Found! Kindly Add the Merchandise Before Assigning');
                            return back();
                        }

                        $product->update([
                            'assigned_to' => $request->team_leader_id,
                            'batch_tl_id' =>DB::table('batch_teamleaders')->where('batch_code', $batch_code)->value('id'),
                            'campaign_id' =>$request->campaign_id, // Attach Campaign Id to product...By Agency
                        ]);
                    }

                    if ($request->brand_id == null) {
                        $product = Product::where('category_id', $request->category_id)
                            ->where('client_id', $request->client_id)
                            ->where('category_id', $request->category_id)
                            ->where('size', $request->size)
                            ->where('color', $request->color)->whereassigned_to(null)->whereowner_id(Auth::id())->whereba_id(null)->first();
                        if ($product == null) {
                            Alert::error('Failed', 'No Merchandise Found! Kindly Add the Merchandise Before Assigning');
                            return back();
                        }

                        $product->update([
                            'assigned_to' => $request->team_leader_id,
                            'batch_tl_id' =>DB::table('batch_teamleaders')->where('batch_code', $batch_code)->value('id'),
                             'campaign_id' =>$request->campaign_id, // Attach Campaign Id to product...By Agency
                        ]);
                    }
                }
                //! Sending to the Assignee (Agency)
                $assigneePhone = Auth::user()->phone;
                $assigneeMessage = 'Batch: ' . $batch_code . ' of ' . $request->quantity . ' ' . DB::table('categories')->whereid($request->category_id)->value('title') . ' assigned to ' . User::whereid($request->team_leader_id)->value('name') . ' Phone: ' . User::whereid($request->team_leader_id)->value('phone');
                $this->sendSMS($assigneePhone, $assigneeMessage);

                //! Sending to the Assigned User (Team Leader)
                $assignedPhone = User::whereid($request->team_leader_id)->value('phone');
                $assignedMessage = 'You have been assigned Merchandise (' . $request->quantity . ' ' . DB::table('categories')->whereid($request->category_id)->value('title') . ') Batch Code: ' . $batch_code . ' by ' . Auth::user()->name . '. Kindly Login to the App by clicking the link : ' . $url_login;
                $this->sendSMS($assignedPhone, $assignedMessage);
                Alert::success('Success', 'Operation Successful');
                return back();
            } else {
                Alert::error('Failed', 'Invalid Merchandise Quantity');
                return back();
            }
        }

        Alert::error('Failed', 'Unauthorized!');
        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $request->validate([
            'client_id' => 'required|integer',
            'category_id' => 'required|integer',
            'assigned_to' => 'required|integer',
        ]);
        if ($product->update($request->all())) {

            Activity::create([
                'title' => 'Merchandise Updated',
                'user_id' => Auth::id(),
                'description' => Auth::user()->name . 'Added Merchandise:' . $product->product_code,
            ]);
            Alert::success('Success', 'Merchandise Updated Successfully');
            return back();
        } else {
            Alert::error('Failed', 'Merchandise Not Updated');
            return back();
        }
    }

    public function sendSMS($receiverNumber, $message)
    {
        try {

            $headers = [
                'Cookie: ci_session=ttdhpf95lap45hq8t3h255af90npbb3ql'
            ];

            $encodMessage = rawurlencode($message);

            $url = 'https://3.229.54.57/expresssms/Api/send_bulk_api?action=send-sms&api_key=Snh2SGFQT0dIZmFtcRGU9ZXBlcEQ=&to=' . $receiverNumber . '&from=IMS&sms=' . $encodMessage . '&response=json&unicode=0&bulkbalanceuser=voucher';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_ENCODING, "");
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true,);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

            $response = curl_exec($ch);
            $res = json_decode($response);
            date_default_timezone_set('Africa/Nairobi');
            $date = date('m/d/Y h:i:s a', time());

            curl_close($ch);
        } catch (Exception $e) {

            return redirect()->back()->with("error", $e);
        }
    }

    public function generateBatchCode()
    {
        $permitted_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $batchcode = 'BAT-' . mt_rand(1000, 9999) . substr(str_shuffle($permitted_chars), 0, 4);

        return $batchcode;
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('products.show',compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('admin_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $product = Product::findOrFail($id);
        $teamleaders = User::where('role_id', 3)->get();
        $clients = Client::all();

        $categories = Category::all();


        return view('products.edit', compact(
            'product',
            'teamleaders',
            'clients',
            'categories',
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function generateProductCode()
    {
        $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $permitted_chars = substr(str_shuffle($permitted_chars), 0, 5);
        $code = mt_rand(100000, 999999) . $permitted_chars;

        return $code;
    }

    public function storeBas(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|numeric',
            'assigned_to' => 'required|numeric',
        ]);
        $quantity = $request->quantity;
        $url_login = URL::to('/login');
        //Get all product with the request batch id & Filter unassigned Product
        $productBas = Productbas::select('product_id')->where('batch_id', $request->batch_id)->get();
        $productsCount = Product::where('products.batch_id', $request->batch_id)->whereNotIn('products.id', $productBas)
            ->join('batches', 'batches.id', 'products.batch_id')
            ->where('batches.tl_id_accept', Auth::id())->where('batches.accept_status', 1)->get();
        // dd($productBas);
        if ($quantity > 0 && $quantity <= count($productsCount)) {
            $dataProducts = [];
            $products = Product::where('batch_id', $request->batch_id)->whereNotIn('id', $productBas)->take($quantity)->get();
            //dd($products);
            foreach ($products as $product) {
                $data = Productbas::create([
                    'batch_id' => $request->batch_id,
                    'assigned_to' => $request->assigned_to,
                    'product_id' => $product->id,
                    'created_at' => Carbon::now(),
                ]);
                Activity::create([
                    'title' => 'Assign Merchandise',
                    'user_id' => Auth::id(),
                    'description' => Auth::user()->name . ' have assigned merchandise: ' . $data->product->product_code . ' to ' . $data->user->email,
                ]);
                array_push($dataProducts, $data);
            }

            if (count($dataProducts) > 0) {
                $receiver_email = User::where('id', $request->assigned_to)->value('email');
                $receiver_phone = User::where('id', $request->assigned_to)->value('phone');
                $sender_email = Auth::user()->email;
                //Add Message for assigning merchandises : includes merchandise type, batch_code quantity
                $merchandise = array_pop($dataProducts);
                //Get the item type in batch
                $merchandise_type = $merchandise->product->category->title;
                $campaign = $merchandise->product->campaign->name;
                $batchcode = Batch::where('id', $request->batch_id)->value('batch_code');
                $message = "Hello, You have been assigned $quantity Merchandises ($merchandise_type) from Batch-Code $batchcode for $campaign. Kindly Confirm through the portal: $url_login";
                $details = [
                    'title' => 'Mail from ' . $sender_email,
                    'body' => $message,
                ];
                //Send Mail
                //Mail::to($receiver_email)->send(new AssignMerchandise($details));

                //Send SMS
                $sms = $this->sendSMSService->sendSMS($message,$receiver_phone);

                Alert::success('Success', 'Merchandises Assigned Successfully to: ' . $receiver_phone);
                return back();
            } else {
                Alert::error('Error', 'Merchandise not Successfully Assigned');
                return back();
            }
        } else {
            Alert::error('Error', 'Merchandises is Not Confirmed or Quantity exceeds maximum: ' . count($productsCount));
            return back();
        }
    }

    // ? Team Leader Confirms a single product

    public function reject(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update([
            'accept_status' => 0,
        ]);

        $reason = Reject::create([
            'reason_id' => $request->reason_id,
            'user_id' => Auth::id(),
            'description' => $request->description,
            'product_id' => $product->id,
        ]);
        Activity::create([
            'title' => 'Reject Merchandise',
            'user_id' => Auth::id(),
            'description' => Auth::user()->name . ' have rejected merchandise: ' . $product->product_code,
        ]);
        $adminIMS = User::where('role_id',1)->first();
        $merchandise_type = $product->category->title;
        $batchcode = $product->batch->batch_code;
        $product_code = $product->product_code;
        $sender_email = Auth::user()->email;
        $receiver_email = $product->assign->email??$adminIMS->email;
        $receiver_phone = $product->assign->phone??$adminIMS->phone;
        $url_login = URL::to('/login');
        $message = "Hello, Merchandise ($merchandise_type), $product_code from Batch-Code $batchcode, has been rejected by $sender_email. Kindly Confirm through the portal: $url_login.";
        $details = [
            'title' => 'Mail From ' . $sender_email,
            'body' => $message,
        ];
         //Send SMS
         $sms = $this->sendSMSService->sendSMS($message,$receiver_phone);
         //Send MAil
        //Mail::to($receiver_email)->send(new AssignMerchandise($details));
        Alert::success('Success', 'Operation Succesfull Details has been sent to ' . $receiver_phone);
        return back();
    }
    //Brand Ambassador rejects Merrchandise in batch
    // Confirm multiple products with batch code assigned to the user

    public function confirm($id)
    {
        $product = Product::findOrFail($id);

        $product->update([
            'accept_status' => 1,
        ]);

        if ($product) {
            Activity::create([
                'title' => 'Merchandise Comfirmed',
                'user_id' => Auth::id(),
                'description' => Auth::user()->name . ' have accepted merchandise: ' . $product->product_code,
            ]);
            Alert::success('Success', 'Operation Successfull.');
            return back();
        }
    }

    //Brand Ambassador rejects Merrchandise in batch

    public function confirmBatch($id)
    {
        // confirm batch for Team Leaders
        $info = 'confirmed';
        //Get List of products to be accepted
        if (Gate::allows('team_leader_access')) {
            $products = Product::where('batch_tl_id', $id)->where('accept_status', 0)->get();
//            $products = Productbas::select('*')->whereIn('product_id', $productaccepted)->get();
            if ($products->count() > 0) {
                //Confirm and update individual products in the Batch
                foreach ($products as $product) {
                    $product = Product::findOrFail($product->id);

                    $product->update([
                        'accept_status' => 1,
                    ]);
                    $batchTL = DB::table('batch_teamleaders')->whereid($id)->update([
                       'accept_status' => 1,
                    ]);
                    Activity::create([
                        'title' => 'Merchandise Comfirmed',
                        'user_id' => Auth::id(),
                        'description' => Auth::user()->name . ' have accepted merchandise: ' . $product->product_code,
                    ]);
                }
                Alert::success('Success', 'Operation Successfull.');
                return back();
            } else {
                Alert::error('Failed', 'No products in Batch');
                return back();
            }
        }


        // confirm batch for Team Leaders

        //Get List of products to be accepted
        if (Gate::allows('brand_ambassador_access')) {


          //Select all products belonging to a particular BAs in Batch
            $products = Product::select('products.*')->where('batch_ba_id', $id)->join('batch_brandambassadors','batch_brandambassadors.id','products.batch_ba_id')
                ->where('batch_brandambassadors.accept_status', 0)->get();
            // = Productbas::select('*')->whereIn('product_id', $productaccepted)->where('assigned_to', Auth::id())->get();
            $productsCount = $products->count();
            if ($productsCount > 0) {
                //Confirm  in the Batch

                $batchBA = DB::table('batch_brandambassadors')
                    ->whereid($id)->first();
                $batchCode = $batchBA->batch_code;
                    DB::table('batch_brandambassadors')
                        ->whereid($id)->update([
                        'accept_status' => 1,
                        'reject_status' => 0,
                        ]);
                    Activity::create([
                        'title' => 'Batch Comfirmed',
                        'user_id' => Auth::id(),
                        'description' => Auth::user()->name . ' have accepted Batch: ' . $batchBA->batch_code,
                    ]);
                //Send Message FeedBack
                $this->sendMessage($products,$productsCount,$batchCode,$info);
                Alert::success('Success', 'Operation Successfull.');
                return back();
            } else {
                Alert::error('Failed', 'Products In Batch Confirmed or No Products in Batch');
                return back();
            }
        }
    }

    public function rejectBatch(Request $request, $id)
    {
        $info = 'rejected';
        //Team Leader Reject Batch.
        if (Gate::allows('team_leader_access')) {
            $products = Product::select('*')->where('batch_tl_id', $id)->where('accept_status', 0)->get();

//        $products = Productbas::select('*')->whereIn('product_id', $productaccepted)->where('assigned_to', Auth::id())->get();
            // dd($products);\
            $productsCount = $products->count();
            if ( $productsCount > 0) {
                $rejectBatch = DB::table('batch_teamleaders')->whereid($id)->update([
                    'reject_status' => 1,
                ]);
                $batchCode = \DB::table('batch_teamleaders')->whereid($id)->value('batch_code');
                foreach ($products as $product) {
                    $product = Product::findOrFail($product->id);
                    $product->update([
                        'accept_status' => 0,
                    ]);
                    $reason = Reject::create([
                        'reason_id' => $request->reason_id,
                        'user_id' => Auth::id(),
                        'description' => $request->description,
                        'product_id' => $product->id,
                    ]);
                    Activity::create([
                        'title' => 'Merchandise Rejected',
                        'user_id' => Auth::id(),
                        'description' => Auth::user()->name . ' have rejected ' . $product->product_code,
                    ]);
                }
                $this->sendMessage($products,$productsCount,$batchCode,$info);
                Alert::success('Success', 'Operation Successfull');
                return back();
            } else {
                Alert::error('Failed', 'Batch is Aready Confirmed');
                return back();
            }
        }

        //Brand Ambassador
        if (Gate::allows('brand_ambassador_access')) {
            $products = Product::select('products.*')->where('products.batch_ba_id', $id)
                                ->join('batch_brandambassadors', 'batch_brandambassadors.id','products.batch_ba_id' )
                                ->where('batch_brandambassadors.brand_ambassador_id',Auth::id())
                                ->where('batch_brandambassadors.accept_status','!=',1)
                                ->get();
//              $products = Productbas::select('*')->whereIn('product_id', $productaccepted)->where('assigned_to', Auth::id())->get();
//             dd($products);
            $productsCount = $products->count();
            if ($productsCount > 0) {
                $rejectBatch = DB::table('batch_brandambassadors')->whereid($id)->update([
                   'reject_status' => 1,
                ]);
                $batchCode = \DB::table('batch_brandambassadors')->whereid($id)->value('batch_code');

                foreach ($products as $product) {
                    $product = Product::findOrFail($product->id);
//                    $product->update([
//                        'accept_status' => 0,
//                    ]);
                    $reason = Reject::create([
                        'reason_id' => $request->reason_id,
                        'user_id' => Auth::id(),
                        'description' => $request->description,
                        'product_id' => $product->id,
                    ]);
                    Activity::create([
                        'title' => 'Merchandise Rejected',
                        'user_id' => Auth::id(),
                        'description' => Auth::user()->name . ' have rejected ' . $product->product_code,
                    ]);
                }

                $this->sendMessage($products,$productsCount,$batchCode,$info);
                Alert::success('Success', 'Operation Successfull');
                return back();
            } else {
                Alert::error('Failed', 'Batch is Aready Confirmed');
                return back();
            }
        }
    }

    // ? Brand Ambassador Issue Product

    public function issueBatch(Request $request)
    {
        $validated = $request->validate([
            'batch_id' => 'required|integer',
            'quantity' => 'required|integer',
        ]);
        // Get Products belonging to a brand Ambassador
        $productsBa = Productbas::select('product_id')->where('assigned_to', Auth::id())->get();

        //Get Issued Products By a Brand Ambassodor
        $issuedProducts = IssueProduct::select('product_id')->where('ba_id', Auth::id())->get();
        //Filter Out Products not issued but belongs to the selected batch and the logged in Brand Ambassador
        $productsBas = Product::select('*')->whereIn('id', $productsBa)->where('batch_ba_id', $request->batch_id)->whereNotIn('id', $issuedProducts)->get();
        //Check if Batch issue doesn't exceed the expected amount
        if ($request->quantity <= count($productsBas)) {
            $issuedProducts = IssueProduct::select('product_id')->where('ba_id', Auth::id())->get();
            // dd($issuedProducts);
            $productsBa = Productbas::select('product_id')->where('assigned_to', Auth::id())->get();
            $productsBas = Product::select('*')->whereIn('id', $productsBa)->whereNotIn('id', $issuedProducts)->take($request->quantity)->get();

            foreach ($productsBas as $product) {
                IssueProduct::create([
                    'batch_id' => $request->batch_id,
                    'ba_id' => Auth::id(),
                    'product_id' => $product->id,
                    'category_id' => $product->category->id,
                ]);
                Activity::create([
                    'title' => 'Merchandise Issued',
                    'user_id' => Auth::id(),
                    'description' => Auth::user()->name . ' have issued out ' . $product->product_code,
                ]);
            }
            Alert::success('Success', 'Operation Successfull');
            return back();
        } else {
            Alert::error('Failed', 'Quantity Entered Exceeds Merchandise in Stock. Maximum: ' . count($productsBas));
            return back();
        }
    }
    public function issueProductCustomer($product_id, $batch_id)
    {
        $product = Product::findOrFail($product_id);
        $batch = BatchBrandambassador::findOrFail($batch_id);

        return view('products.issue-customer',compact('product','batch'));
    }
    public function issueProduct(Request $request)
    {
        $permissionName = 'Issue Merchandise';
        $permissions = $this->permissionsService->getPermissions($permissionName);
        abort_unless($permissions, 403);

        $product = Product::findOrFail($request->product_id);
        $productIssued = IssueProduct::where('product_id',$request->product_id)->get();
        if ($productIssued->count()>0){
            Alert::error('Failed','Merchandise Has already been issued out');
            return redirect()->route('products.index');
        }
        $batch = BatchBrandambassador::findOrFail($request->batch_id);


        //Check outlet Radius and reject if greater
        $outlet = Outlet::whereid($request->outlet)->first();

        $distanceDifference = $this->getLocationDistance->vincentyGreatCircleDistance(
            $outlet->address_latitude, $outlet->address_longitude, $request->latitude, $request->longitude, $earthRadius = 6371000);
        //dd($distanceDifference);
        if($distanceDifference > 0.2){ //If location distance is greater than 500 meters || Set this value depending on the Application outlet radius
            Alert::error('Failed', 'Issue Merchandise Out of Outlet Area Range. Distance From Outlet: '.$distanceDifference.' kms');
            return redirect()->route('products.index');
        }
        //Check if customer phone exist in DB and Reject
        if (!empty($request->customer_phone)){
            $customerPhone = $request->customer_phone;
            $customer = Customer::with('product.campaign')->select('product_id')->where('phone',$customerPhone)->get();
            //Check Campaign Associated with the customer.
            if ($customer->count()>0){
                Alert::error('Failed','Customer Has Been Issued Merchandise');
                return back();
            }


            Customer::create([
                'name' => $request->customer_name,
                'phone' => $request->customer_phone,
                'product_id' => $product->id,
            ]);
        }
        //Get BA Issueing Location.
        $location = geoip($request->ip());
       // $location = geoip('8.8.8.8'); //testing

        IssueProduct::create([
            'ba_id' => Auth::id(),
            'batch_id' => $batch->id,
            'product_id' => $product->id,
            'category_id' => $product->category->id,
            'ip-address' => $location->ip??'',
            'longitude' => $location->lon??'',
            'latitude' => $location->lat??'',
            'state_name' => $location->state_name??'',
            'postal_code' => $location->postal_code??'',
            'city' => $location->city??'',
            'outlet_id' => $request->outlet,
        ]);
        Activity::create([
            'title' => 'Merchandise Issued',
            'user_id' => Auth::id(),
            'description' => Auth::user()->name . ' have issued out ' . $product->product_code,
        ]);
        Alert::success('Success', 'Operation Successfull');
        return redirect()->route('products.index');
    }

    public function createUpload()
    {
        $teamleaders = User::where('role_id', 3)->get();
        $clients = Client::all();
        $categories = Category::where('client_id', null)->get();
        $categoriesClient = Category::where('client_id', Auth::user()->client_id)->get();

        $brands = Brand::all();
        $brandsClient = Brand::where('client_id', Auth::user()->client_id)->get();
        $storages = Storage::get();
        $storagesClient = Storage::where('client_id', Auth::user()->client_id)->get();
        $user_id = Auth::id();


        $brandAmbassadors = User::where('role_id', 4)->where('teamleader_id', $user_id)->get();
        $batches = Product::select('batch_id', 'batch_code')->where('assigned_to', Auth::id())->join('batches', 'batches.id', 'products.batch_id')->groupBy('batch_id')->get();
        return view('products.create-upload', compact(
            'teamleaders',
            'categories',
            'categoriesClient',
            'clients',
            'brandAmbassadors',
            'batches',
            'storages',
            'storagesClient',
            'brandsClient',
            'brands'
        ));
    }

    //FUnction to Send SMS

    public function uploadMerchandise(Request $request)
    {
        $assigned_product = DB::table('products')->where('product_code', $request->code)->get();
        //dd($assigned_product->count());

        if ($assigned_product->count() != 0) {
            Alert::error('Error', 'Merchandise Code Already Uploaded');
            return back();
        }
        $product = DB::table('product_codes')->where('product_code', $request->code)->get();
        if ($product->count() != 0) {
            $product_upload = Product::where('category_id', $request->category_id)->where('client_id', $request->client_id)->where('product_code', null)->first();
            if ($product_upload->count() != 0) {
                $product_upload->update([
                    'product_code' => $request->code,
                    'brand_id' => $request->brand_id
                ]);
                Alert::success('Success', 'Operation Successfull');
                return back();
            } else {
                Alert::error('Failed', 'No Merchandise Found');
                return back();
            }
        } else {
            Alert::error('Failed', 'No Merchandise Found with that code');
            return back();
        }
    }

    public function sendMessage($products,$productsCount,$batchCode,$info){
        $adminIMS = User::where('role_id',1)->first();
        $product = $products->first();
        $productsCount = $products->count();
        $merchandise_type = $product->category->title;
        $sender_email = Auth::user()->email;
        $receiver_email = $product->assign->email??$adminIMS->email;
        $receiver_phone = $product->assign->phone??$adminIMS->phone;
        $url_login = URL::to('/login');
        $message = "Hello, Merchandise ($merchandise_type), $productsCount from Batch-Code $batchCode, has been $info by $sender_email. Kindly Confirm through the portal: $url_login.";
        $details = [
            'title' => 'Mail From ' . $sender_email,
            'body' => $message,
        ];
       // Send Mail
       // Mail::to($receiver_email)->send(new AssignMerchandise($details));

       //Send SMS
       $sms = $this->sendSMSService->sendSMS($message,$receiver_phone);
    }
}
