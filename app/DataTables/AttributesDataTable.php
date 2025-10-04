<?php

namespace App\DataTables;

use App\Models\Attribute;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AttributesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Attribute> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
             return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                return '<div class="d-flex justify-content-between ">
            <a href="' . route('admin.attributes.edit', $query->id) . '"  class="btn btn-primary btn-md edit-attribute-btn" data-bs-toggle="modal" data-bs-target="#edit-attribute" style="margin-right:4px;"><i class="fas fa-edit"></i></a>
            <a href="' . route('admin.attributes.destroy', $query->id) . '" class="btn btn-danger btn-md" id="confirmation" data-datatable_id="#attributes-table"><i class="fas fa-trash" ></i></a>
            </div>';
            })->addColumn('name', function ($query) {
                return $query->name ? $query->getTranslation('name', 'en') : '';
            })
            ->rawColumns(['action', 'status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Attribute>
     */
    public function query(Attribute $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('attributes-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
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
            Column::make('name'),
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
        return 'Attributes_' . date('YmdHis');
    }
}
