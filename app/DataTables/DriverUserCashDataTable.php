<?php

namespace App\DataTables;

use App\Models\DriverUserCash;
use App\Models\UserDriverCash;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DriverUserCashDataTable extends DataTable
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

            ->editColumn('status', function ($row) {
                return( $row->status == 'completed' )? 'تم التحويل' : "لم يتم التحويل" ;
            })

            ->editColumn('created_at', function ($meal) {
                return  $meal->created_at->toDateString();
            })
            ->editColumn('updated_at', function ($meal) {
                return  $meal->updated_at->toDateString();
            })
            ->editColumn('action', function ($row) {
                $btns =" <a href='".(route('admin.orders.show' , $row->order_id))."' class='dropdown-item'> <i class='ph-list-bullets me-2'></i>  تفاصيل الاوردر</a>";
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
            ]);

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\DriverUserCash $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(UserDriverCash $model): QueryBuilder
    {
        return $model->newQuery()
            ->whereUserId($this->user->id)
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

            Column::make('total_cash')->title('القيمة'),


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
        return 'DriverUserCash_' . date('YmdHis');
    }
}
