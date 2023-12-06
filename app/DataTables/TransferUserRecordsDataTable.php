<?php

namespace App\DataTables;

use App\Models\TransferRecord;
use App\Models\TransferUserRecord;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TransferUserRecordsDataTable extends DataTable
{

    protected $user ;

    /**
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
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
            ->editColumn('admin_checked', function ($row) {
                return $row->admin_checked ? 'تم التاكيد' : "لم يتم التاكيد" ;
            })
            ->editColumn('transfer_status', function ($row) {
                return( $row->transfer_status == 'completed' )? 'تم التحويل' : "لم يتم التحويل" ;
            })
            ->editColumn('admin_notes', function ($row) {
                if (!$row->admin_notes) return  '-';
                return "<a class='btn btn-link' onclick='showNote(`$row->admin_notes`)'> ". str($row->admin_notes)->limit(50). " </a>";
            })

            ->editColumn('created_at', function ($meal) {
                return  $meal->created_at->toDateString();
            })
            ->editColumn('updated_at', function ($meal) {
                return  $meal->updated_at->toDateString();
            })
            ->editColumn('transfer_date', function ($meal) {
                if (!$meal->transfer_date) return  ;
                return  $meal->transfer_date->toDateString();
            })



            ->editColumn('action', function ($row) {

                $btns =" <a href='".(route('admin.orders.show' , $row->order_id))."' class='dropdown-item'> <i class='ph-list-bullets me-2'></i>  تفاصيل الاوردر</a>";

                if ($row->transaction_id)
                $btns .=" <a href='".(route('admin.transactions.show' , $row->transaction_id))."' class='dropdown-item'> <i class='ph-eye me-2'></i>تفاصيل دفع الطلب</a>";


                if ($row->transfer_id)
                $btns .=" <a href='".(route('admin.transactions.show' , $row->transfer_id))."' class='dropdown-item'> <i class='ph-eye me-2'></i>تفاصيل التحويل لحسابه</a>";

                if (!$row->admin_checked)
                    $btns .=" <a href='#' onclick='adminChecked(`".(route('admin.transfer.records_checked' , $row->id))."`)' class='dropdown-item'> <i class='ph-check me-2'></i>تأكيد استحقاق الحركه</a>";



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
                'admin_notes',
                'action',
            ]);





    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\TransferUserRecord $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(TransferRecord $model): QueryBuilder
    {
        return $model->newQuery()
            ->whereToId($this->user->id)
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
            ->ajaxWithForm(url()->current(), '#filter_form')
            ->orderBy(0)
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
            Column::make('order_id')->title('رقم الاوردر'),
            Column::make('amount')->title('القيمة'),
            Column::make('percent')->title('#نسبة الادمن'),
            Column::make('admin_checked')->title('تاكيد الاستحقاق'),
            Column::make('admin_notes')->title('ملاحظات الادمن'),
            Column::make('transfer_status')->title('حاله التحويل لحسابة'),
            Column::make('transfer_date')->title('تاريخ التحويل لحسابة'),
            Column::make('created_at')->title('تاريخ انشاء السجل'),
            Column::make('updated_at')->title('تاريخ اخر تحديث'),

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
        return 'TransferUserRecords_' . date('YmdHis');
    }
}
