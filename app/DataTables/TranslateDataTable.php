<?php

namespace App\DataTables;

use App\Models\Translate;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TranslateDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('en', function ($translation) {
                return  $translation->en ?? '-';
            })

            ->editColumn('ar', function ($translation) {
                return  $translation->ar ?? '-';
            })

            ->editColumn('fr', function ($translation) {
                return  $translation->fr ?? '-';
            })

            ->editColumn('tr', function ($translation) {
                return  $translation->tr ?? '-';
            })

            ->editColumn('action', function ($translation) {
                $deleteRoute = route('translations.destroy', $translation->id);
                $editLink = 'EditFunction(' . htmlspecialchars(json_encode($translation), ENT_QUOTES, 'UTF-8') . ');';
                $editLabel = __('messages.admin_edit');
                $deleteLabel = __('messages.admin_delete');

                return <<<HTML
                <div class="d-inline-flex">
                        <div class="dropdown">
                            <a href="#" class="text-body" data-bs-toggle="dropdown">
                                <i class="ph-list"></i>
                            </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#" class="dropdown-item" onclick="{$editLink}"> <i class="ph-pencil me-2"></i> $editLabel </a>
                            <a href="#" class="dropdown-item text-danger" onclick="DeleteFunction('$deleteRoute');"> <i class="ph-trash me-2"></i> $deleteLabel</a>
                        </div>
                    </div>
                </div>
                HTML;
            })
            ->setRowId('id')
            ;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Translate $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Translate $model): QueryBuilder
    {
        return $model->newQuery();
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
                    ->minifiedAjax()
                    ->orderBy(2)
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('print'),
                        Button::make()
                            ->action("createTranslations()")
                            ->className('btn btn-teal')
                            ->text('<i class="ph-plus"></i> اضافه'),
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
            Column::make('id'),
            Column::make('key')->title('المفتاح'),
            Column::make('model')->title('النموذج'),
            Column::make('en')->title('إنجليزي'),
            Column::make('ar')->title('عربي'),
            Column::make('fr')->title('فرنسي'),
            Column::make('tr')->title('تركي'),
            Column::computed('action')
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
        return 'Translate_' . date('YmdHis');
    }
}
