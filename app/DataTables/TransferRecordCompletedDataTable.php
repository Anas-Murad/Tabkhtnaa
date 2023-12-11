<?php

namespace App\DataTables;

use App\Models\TransferRecord;
use App\Models\TransferRecordCompleted;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TransferRecordCompletedDataTable extends DataTable
{

    protected  $transfer_id ;

    /**
     * @param $transfer_id
     */
    public function __construct($transfer_id)
    {
        $this->transfer_id = $transfer_id;
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

            ->editColumn('created_at', function ($row) {
                return  $row->created_at->toDateString();
            })

            ->editColumn('updated_at', function ($row) {
                return  $row->updated_at->toDateString();
            })

            ->editColumn('transfer_date', function ($row) {
                return  $row->transfer_date->toDateString();
            })





            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\TransferRecordCompleted $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(TransferRecord $model): QueryBuilder
    {
        return $model->newQuery()
            ->whereTransferId($this->transfer_id)
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
            Column::make('admin_notes')->title('ملاحظات الادمن'),
            Column::make('transfer_date')->title('تاريخ التحويل لحسابة'),
            Column::make('created_at')->title('تاريخ انشاء السجل'),
            Column::make('updated_at')->title('تاريخ اخر تحديث'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'TransferRecordCompleted_' . date('YmdHis');
    }
}
