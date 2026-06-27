<?php

namespace App\DataTables;

use App\Models\AuditTrail;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AuditTrailsDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('actor', function (AuditTrail $audit) {
                return e($audit->actor_name);
            })
            ->editColumn('event', function (AuditTrail $audit) {
                $badges = [
                    'created' => 'bg-success',
                    'updated' => 'bg-primary',
                    'deleted' => 'bg-danger',
                    'login' => 'bg-info',
                    'logout' => 'bg-secondary',
                    'password_changed' => 'bg-warning',
                ];
                $class = $badges[$audit->event] ?? 'bg-dark';

                return "<span class='badge {$class} text-white'>{$audit->event}</span>";
            })
            ->editColumn('auditable_label', function (AuditTrail $audit) {
                return e($audit->auditable_label);
            })
            ->editColumn('changes_summary', function (AuditTrail $audit) {
                $old = $audit->old_values ? count($audit->old_values) : 0;
                $new = $audit->new_values ? count($audit->new_values) : 0;

                if ($old === 0 && $new === 0) {
                    return '-';
                }

                return "قديم: {$old} | جديد: {$new}";
            })
            ->editColumn('ip_address', fn (AuditTrail $audit) => e($audit->ip_address ?? '-'))
            ->editColumn('created_at', fn (AuditTrail $audit) => $audit->created_at?->format('Y-m-d H:i:s') ?? '-')
            ->editColumn('action', function (AuditTrail $audit) {
                $data = htmlspecialchars(json_encode($audit), ENT_QUOTES, 'UTF-8');

                return <<<HTML
                <div class="d-inline-flex">
                    <a href="#" class="btn btn-sm btn-light" onclick="showAuditDetails({$data}); return false;">
                        <i class="ph-eye"></i> عرض
                    </a>
                </div>
                HTML;
            })
            ->setRowId('id')
            ->rawColumns(['event', 'action']);
    }

    public function query(AuditTrail $model): QueryBuilder
    {
        return $model->newQuery()
            ->with([
                'user:id,name,email,mobile',
                'admin:id,name,email',
            ])
            ->when($this->request->filled('user_id'), function ($q) {
                $q->where('user_id', $this->request->user_id);
            })
            ->when($this->request->filled('admin_id'), function ($q) {
                $q->where('admin_id', $this->request->admin_id);
            })
            ->when($this->request->filled('event'), function ($q) {
                $q->where('event', $this->request->event);
            })
            ->when($this->request->filled('auditable_type'), function ($q) {
                $q->where('auditable_type', 'like', '%' . $this->request->auditable_type . '%');
            })
            ->when($this->request->filled('search_key'), function ($q) {
                $key = $this->request->search_key;
                $q->where(function ($q) use ($key) {
                    $q->where('ip_address', 'like', "%{$key}%")
                        ->orWhere('url', 'like', "%{$key}%")
                        ->orWhereHas('user', function ($q) use ($key) {
                            $q->where('name', 'like', "%{$key}%")
                                ->orWhere('email', 'like', "%{$key}%")
                                ->orWhere('mobile', 'like', "%{$key}%");
                        })
                        ->orWhereHas('admin', function ($q) use ($key) {
                            $q->where('name', 'like', "%{$key}%")
                                ->orWhere('email', 'like', "%{$key}%");
                        });
                });
            })
            ->when($this->request->filled('from_date'), function ($q) {
                $q->whereDate('created_at', '>=', $this->request->from_date);
            })
            ->when($this->request->filled('to_date'), function ($q) {
                $q->whereDate('created_at', '<=', $this->request->to_date);
            });
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('data-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(6, 'desc')
            ->selectStyleSingle()
            ->ajaxWithForm(url()->current(), '#filter_form')
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('print'),
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('#'),
            Column::computed('actor')->title('المستخدم'),
            Column::make('event')->title('الحدث'),
            Column::computed('auditable_label')->title('الكيان'),
            Column::computed('changes_summary')->title('التغييرات'),
            Column::make('ip_address')->title('IP'),
            Column::make('created_at')->title('التاريخ'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(80)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'AuditTrails_' . date('YmdHis');
    }
}
