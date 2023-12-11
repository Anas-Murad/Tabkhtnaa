<?php

namespace App\DataTables;

use App\Models\Transfer;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TransferDataTable extends DataTable
{
    protected $type;

    public function __construct($type)
    {
        $this->type = $type;
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
            ->editColumn('created_at', function ($complaint) {
                return  $complaint->created_at->toDateString();
            })

            ->editColumn('updated_at', function ($complaint) {
                return  $complaint->updated_at->toDateString();
            })

            ->editColumn('action', function ($row) {

                $btns = " <a href='" . (route('admin.transfer.transfer_records', $row->id)) . "' class='dropdown-item'>
                    <i class='ph-list-checks me-2'></i>تفاصيل السجلات المرتبطه</a>";





                $btns .= " <a href='" . (route('users.show', $row->to_id)) . "' class='dropdown-item'> <i class='ph-eye me-2'></i> ملف العميل</a>";

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



            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Transfer $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Transfer $model): QueryBuilder
    {
        return $model->newQuery()
            ->where('to_type', $this->type)
            ->with('to');
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
//            ->orderBy(0)
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


/*        Full texts

from_type
from_id
to_type
to_id
amount
deleted_at
created_at
updated_at*/

        return [
            Column::make('id')->title('#رقم الحركه'),
            Column::make('to.name')->title('اسم العميل'),
            Column::make('amount')->title('المبلغ المسدد'),
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
        return 'Transfer_' . date('YmdHis');
    }
}
