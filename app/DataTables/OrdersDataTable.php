<?php

namespace App\DataTables;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class OrdersDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */

    protected $status;
    protected $transactionStatus;
    protected $userID;
    protected $user;

    /**
     * @param $status
     * @param $transactionStatus
     * @param $userID
     * @param $user
     */
    public function __construct($status = null, $transactionStatus = null, $userID = null, $user = null)
    {
        $this->status = $status;
        $this->transactionStatus = $transactionStatus;
        $this->userID = $userID;
        $this->user = $user;
    }


    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('payment_method', function ($user) {
                return " <span class='badge bg-info '>" . __('messages.' . $user->payment_method) . "</span>";
            })
            ->editColumn('delivery_type', function ($user) {
                return " <span class='badge bg-indigo'>" . __('messages.delivery_type_' . $user->delivery_type) . "</span>";
            })
            ->editColumn('transaction_status', function ($user) {
                return " <span class='badge bg-pink '>" . __('messages.' . $user->transaction_status) . "</span>";
            })
            /*
            teal
            yellow
            info
            warning
            success
            danger
            secondary
            primary
            pink
            */

            ->editColumn('status', function ($user) {
                return " <span class='badge bg-purple '>" . __('messages.status_' . $user->status) . "</span>";
            })
            ->editColumn('created_at', function ($user) {
                return $user->created_at->toDateString();
            })
            ->editColumn('updated_at', function ($user) {
                return $user->updated_at->toDateString();
            })
            ->editColumn('action', function ($user) {
                $orderLink = (route('admin.orders.show', $user));

                $btns = "";
                $btns .= "<a href='$orderLink' class='dropdown-item'> <i class='ph-shopping-cart me-2'></i> التفاصيل </a>";


                if ($user->transaction_id) {
                    $transactionLink = (route('admin.transaction.order', [$user, $user->transaction_id]));
                    $btns .= "<a href='$transactionLink' class='dropdown-item'> <i class='ph-money me-2'></i> الحركه المالية </a>";
                }


                if ($user->user->id) {
                    $userEditLink = (route('users.show', $user->user->id));
                    $btns .= "<a href='$userEditLink' class='dropdown-item'> <i class='ph-user me-2'></i> ملف العميل </a>";

                }
                if ($user->chef && $user->chef->id) {
                    $chefEditLink = (route('users.show', $user->chef->id));
                    $btns .= "<a href='$chefEditLink' class='dropdown-item'> <i class='ph-cooking-pot me-2'></i> ملف الطاهي </a>";

                }
                if ($user->delivery && $user->delivery->id) {
                    $deliveryEditLink = (route('users.show', $user->delivery->id));
                    $btns .= "<a href='$deliveryEditLink' class='dropdown-item'> <i class='ph-jeep me-2'></i> ملف الموصل </a>";
                }

                return <<<HTML
                <div class="d-inline-flex">
                        <div class="dropdown">
                            <a href="#" class="text-body" data-bs-toggle="dropdown">
                                <i class="ph-list"></i>
                            </a>
                        <div class="dropdown-menu dropdown-menu-end">
                                $btns
                        </div>
                    </div>
                </div>
                HTML;
            })
            ->setRowId('id')
            ->rawColumns([
                'action',
                'payment_method',
                'delivery_type',
                'transaction_status',
                'status',
                'user.name',
            ]);;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Order $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Order $model): QueryBuilder
    {
        return $model->newQuery()
            ->select('orders.*')
            ->with([
                'user:id,name',
                'chef:id,name',
                'delivery:id,name',
                'address' => function ($q) {
                    $q->with([
                        'country:id,name,currency',
                        'cities:id,name',
                    ]);
                },
            ])

            ->when($this->status, function ($q) {
                $q->where('status', $this->status);
            })


            ->when($this->transactionStatus, function ($q) {
                $q->where('transaction_status', $this->transactionStatus);
            })
            ->when($this->userID, function ($q) {

                switch ($this->user->type){
                    case 'client':
                        $q->where('user_id', $this->userID);
                        break;
                    case 'delivery':
                        $q->where('delivery_id', $this->userID);
                        break;
                    case 'chef':
                        $q->where('chef_id', $this->userID);
                        break;
                }
            })


            ->when($this->request->filled('country_id') || $this->request->filled('city_id'), function ($q) {
                $r = [];
                if ($this->request->filled('country_id')) $r['country_id'] = $this->request->country_id;
                if ($this->request->filled('city_id')) $r['city_id'] = $this->request->country_id;
                $q->whereRelation('address', $r);
            })


            ->when($this->request->filled('search_key'), function ($q) {
                $q->where(function ($q) {
                    $q->where(function ($q) {
                        $q->whereHas('user', function ($q) {
                            $q->where('name', 'like', '%' . $this->request()->input('search_key') . '%');
                            $q->orWhere('email', 'like', '%' . $this->request()->input('search_key') . '%');
                            $q->orWhere('country_code', 'like', '%' . $this->request()->input('search_key') . '%');
                            $q->orWhere('mobile', 'like', '%' . $this->request()->input('search_key') . '%');
                            $q->orWhere('username', 'like', '%' . $this->request()->input('search_key') . '%');
                            $q->orWhere('account_comment', 'like', '%' . $this->request()->input('search_key') . '%');
                        })->orWhereHas('chef', function ($q) {
                            $q->where('name', 'like', '%' . $this->request()->input('search_key') . '%');
                            $q->orWhere('email', 'like', '%' . $this->request()->input('search_key') . '%');
                            $q->orWhere('country_code', 'like', '%' . $this->request()->input('search_key') . '%');
                            $q->orWhere('mobile', 'like', '%' . $this->request()->input('search_key') . '%');
                            $q->orWhere('username', 'like', '%' . $this->request()->input('search_key') . '%');
                            $q->orWhere('account_comment', 'like', '%' . $this->request()->input('search_key') . '%');
                        })->orWhereHas('delivery', function ($q) {
                            $q->where('name', 'like', '%' . $this->request()->input('search_key') . '%');
                            $q->orWhere('email', 'like', '%' . $this->request()->input('search_key') . '%');
                            $q->orWhere('country_code', 'like', '%' . $this->request()->input('search_key') . '%');
                            $q->orWhere('mobile', 'like', '%' . $this->request()->input('search_key') . '%');
                            $q->orWhere('username', 'like', '%' . $this->request()->input('search_key') . '%');
                            $q->orWhere('account_comment', 'like', '%' . $this->request()->input('search_key') . '%');
                        });
                    });
                });
            })


            ->when($this->request->filled('from_date'), function ($q) {
                $q->where('created_at', '>=', $this->request()->input('from_date'));
            })
            ->when($this->request->filled('to_date'), function ($q) {
                $q->where('created_at', '<=', $this->request()->input('to_date'));
            })
            ->when($this->request->filled('status'), function ($q) {
                $q->where('status', $this->request->input('status'));
            })
            ->when($this->request->filled('transaction_status'), function ($q) {
                $q->where('transaction_status', $this->request->input('transaction_status'));
            })
            ->when($this->request->filled('delivery_type'), function ($q) {
                $q->where('delivery_type', $this->request->input('delivery_type'));
            });
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('data-table')
            ->columns($this->getColumns())
            ->ajaxWithForm(url()->current(), '#filter_form')
//                    ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel')->className('btn btn-dark')->text('<i class="ph-microsoft-excel-logo"></i> EXCEL'),
                Button::make('csv')->className('btn btn-info')->text('<i class="ph-file-csv"></i> CSV'),
                Button::make('print')->className('btn btn-success')->text('<i class="ph-printer"></i> طباعة'),
                Button::make('colvis')->className('btn btn-teal')->text('<i class="ph-list"></i>'),
            ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('#ID'),
            Column::make('user.name')->content('-')->title('المستخدم'),
            Column::make('chef.name')->content('-')->title('الشيف'),
            Column::make('delivery.name')->content('-')->title('السائق'),
            Column::make('status')->content('-')->title('حالة الطلب'),
            Column::make('transaction_status')->content('-')->title('حاله الدفع'),
            Column::make('payment_method')->content('-')->title('طريقة الدفع'),
            Column::make('delivery_type')->content('-')->title('نوع التوصيل'),
            Column::make('delivery_fees')->content('-')->title('عمولة التوصيل'),
            Column::make('tax')->content('-')->title('الضريبه'),
            Column::make('sub_total')->content('-')->title('مجموع'),
            Column::make('discount')->content('-')->title('خصم'),
            Column::make('total')->content('-')->title('احمالي'),

            Column::make('expected_order_time')->content('-')->title('وقت الطلب المتوقع'),
            Column::make('estimated_delivery_time')->content('-')->title('وقت التوصيل المتوقع'),
            Column::make('estimated_time')->content('-')->title('وقت المتوقع'),


            Column::make('address.country.name')->content('-')->title('الدولة')->orderable(false),
            Column::make('address.country.currency')->content('-')->title('العملة')->orderable(false),
            Column::make('address.cities.name')->content('-')->title('المدينه')->orderable(false),


//            Column::make('coupon_id'),
//            Column::make('coupon'),
//            Column::make('details'),
//            Column::make('transaction_id'),
//            Column::make('rejected_reason'),


            Column::make('created_at')->title('تاريخ الانشاء'),
            Column::make('updated_at')->title('تاريخ اخر تعديل'),
            Column::computed('action')->title('الخيارات')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),


        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Orders_' . date('YmdHis');
    }


}
