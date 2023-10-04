<?php

namespace App\DataTables;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TransactionDataTable extends DataTable
{


    protected  $status ;

    /**
     * @param $status
     */
    public function __construct($status)
    {
        $this->status = $status;
    }

    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', function ($Transaction) {
                return $Transaction->created_at->toDateString();
            })
            ->editColumn('updated_at', function ($Transaction) {
                return $Transaction->updated_at->toDateString();
            })
            ->editColumn('admin_notes', function ($Transaction) {
                if (!$Transaction->admin_notes) return  '-';
                return "<a class='btn btn-link' onclick='showNote(`$Transaction->admin_notes`)'> ". str($Transaction->admin_notes)->limit(50). " </a>";
            })

            ->editColumn('status', function ($Transaction) {
                switch ( $Transaction->status){
                    case 'pending':
                        return " <span class='badge bg-info  text-white'> بانتظار الدفع</span>";

                    case 'success':
                        return " <span class='badge bg-success  text-white'>  تم الدفع</span>";

                    case 'failed':
                        return " <span class='badge bg-danger  text-white'>  تم الغاء الدفع</span>";

                }
            })

            ->editColumn('admin_status', function ($Transaction) {
                switch ( $Transaction->status){
                    case 'pending':
                        return " <span class='badge bg-info  text-white'>بانتظار التاكيد</span>";

                    case 'success':
                        return " <span class='badge bg-success  text-white'>تم التاكيد</span>";

                    case 'failed':
                        return " <span class='badge bg-danger text-white'>تم  رفض التاكيد</span>";

                }
            })

            ->editColumn('payment_method', function ($Transaction) {
                switch ( $Transaction->payment_method){
                    case 'wallet':
                        return " <span class='badge bg-info  text-white'> محفظة العميل</span>";

                    case 'cash':
                        return " <span class='badge bg-primary  text-white'>كاش</span>";

                    case 'cards':
                        return " <span class='badge bg-success  text-white'>بطاقات إئمانية</span>";
                }
            })



            ->editColumn('user.email', function ($Transaction) {
                $email = $Transaction->user->email;
                return  "<a href='mailto:$email'>$email</a>";
            })

            ->editColumn('user.mobile', function ($Transaction) {
                $mobile = $Transaction->user->country_code;
                $mobile .= $Transaction->user->mobile;
                return  "<a href='tel:$mobile'>$mobile</a>";
            })

            ->editColumn('action', function ($Transaction) {

                $btns =" <a href='".(route('admin.transactions.show' , $Transaction->id))."' class='dropdown-item'><i class='ph-money me-2'></i> الحركه المالية</a>";
                $btns .=" <a href='".(route('users.show' , $Transaction->user_id))."' class='dropdown-item'> <i class='ph-eye me-2'></i> تفاصيل المستخدم</a>";
                if($Transaction->order_id )
                $btns .=" <a href='".(route('admin.orders.show' , $Transaction->order_id))."' class='dropdown-item'> <i class='ph-baseball me-2'></i> تفاصيل الطلب</a>";







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
                'status',
                'admin_status',

                'user.email',
                'user.mobile',
                'payment_method',
                'type',
                'admin_notes',
                'account_status',
                'can_delivery',
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Transaction $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Transaction $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('user.residenceCountry')


            ;
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
//            ->ajaxWithForm(url()->current(), '#filter_form')
                    //->dom('Bfrtip')
                    ->orderBy(0)
                   // ->selectStyleSingle()
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
            Column::make('order_id')->title('#رقم الطلب'),
            Column::make('payment_id')->title('#رقم الحركة'),

            Column::make('user.name')->title('الاسم'),
            Column::make('user.username')->title('اسم المتسخدم'),
            Column::make('user.email')->title('البريد'),
            Column::make('user.mobile')->title('رقم الهاتف'),
            Column::make('user.residence_country.name')->title('بلد الاقامة') ->orderable(false) ->searchable(false)  ->exportable(false) ->printable(false),

            Column::make('payment_method')->title('وسيلة الدفع'),
            Column::make('service_type')->title('الخدمة'),
            Column::make('amount')->title('المبلغ'),
            Column::make('currency')->title('العملة'),
            Column::make('status')->title('حالة الحركة'),
            Column::make('admin_status')->title('تاكيد الادمن') ,
            Column::make('admin_notes')->title('ملاحظات الادمن')->orderable(false) ->searchable(false)  ->exportable(false) ->printable(false),
            Column::make('tried_again')->title(' اعاده المحاولة'),
            Column::make('created_at')->title('تاريخ'),

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
        return 'Transaction_' . date('YmdHis');
    }
}
