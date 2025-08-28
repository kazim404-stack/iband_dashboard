<?php

namespace App\DataTables;

use App\Models\GeneralSetting;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class GeneralSettingsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<GeneralSetting> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                return '<div class="d-flex justify-content-between ">
            <a href="' . route('admin.general-settings.edit', $query->id) . '"  class="btn btn-primary btn-md edit-general-setting-btn" data-bs-toggle="modal" data-bs-target="#edit-general-setting" style="margin-right:4px;"><i class="fas fa-edit"></i></a>
            <a href="' . route('admin.general-settings.destroy', $query->id) . '" class="btn btn-danger btn-md" id="confirmation" data-datatable_id="#generalsettings-table"><i class="fas fa-trash" ></i></a>
            </div>';
            })->addColumn('logo', function ($query) {
                return '<img src="' . asset($query->logo) . '" alt="logo" width="150" />';
            })
            ->rawColumns(['logo', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<GeneralSetting>
     */
    public function query(GeneralSetting $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('generalsettings-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->parameters([
                'scrollX' => "true",
                "scrollCollapse" => "false",
                "autowidth" => true,
                "dom" => "Blfrtip",
            ])
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [

            Column::make('id'),
            Column::make('site_name'),
            Column::make('logo'),
            Column::make('facebook'),
            Column::make('telegram'),
            Column::make('instagram'),
            Column::make('whatsapp'),
            Column::make('linkedin'),
            Column::make('x'),
            Column::make('youtube'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'GeneralSettings_' . date('YmdHis');
    }
}
