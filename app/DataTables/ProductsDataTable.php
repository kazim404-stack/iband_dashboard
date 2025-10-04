<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Product> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                return '<div class="d-flex justify-content-between ">
            <a href="' . route('admin.products.edit', $query->id) . '"  class="btn btn-primary btn-md edit-product-btn" data-bs-toggle="modal" data-bs-target="#edit-product" style="margin-right:4px;"><i class="fas fa-edit"></i></a>
            <a href="' . route('admin.products.destroy', $query->id) . '" class="btn btn-danger btn-md" id="confirmation" data-datatable_id="#products-table"><i class="fas fa-trash" ></i></a>
                          <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle"
                data-bs-toggle="dropdown"
                aria-expanded="false">
            <i class="fas fa-cog"></i>
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="' . route('admin.products.images.index', $query->id) . '"><i class="fas fa-image"></i> Image</a></li>
        </ul>
    </div>
            </div>';
            })->addColumn("name", function ($query) {
                return $query->name ? $query->getTranslation('name', 'en') : '';
            })->addColumn("short_description", function ($query) {
                return $query->short_description ? $query->getTranslation('short_description', 'en') : '';
            })->filterColumn('name', function ($query, $keyword) {
                $query->whereRaw(
                    "LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, '$.\"en\"'))) LIKE ?",
                    ["%" . strtolower($keyword) . "%"]
                );
            })->filterColumn('category', function ($query, $keyword) {
                $query->whereHas('category', function ($q) use ($keyword) {
                    $q->whereRaw(
                        "LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, '$.\"en\"'))) LIKE ?",
                        ["%" . strtolower($keyword) . "%"]
                    );
                });
            })
            ->addColumn("category", function ($query) {
                return $query->category ? $query->category->getTranslation('name', 'en') : '';
            })->addColumn("status", function ($query) {
                return $query->status == 1 ? '<span class="badge bg-success text-white">Active</span>' : '<span class="badge bg-danger text-white">Inactive</span>';
            })->rawColumns(['description', 'action', 'status']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Product>
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('products-table')
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
            Column::make('category'),
            Column::make('sku'),
            Column::make('type'),
            Column::make('weight'),
            Column::make('length'),
            Column::make('width'),
            Column::make('height'),
            Column::make('short_description'),
            Column::make('status'),
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
        return 'Products_' . date('YmdHis');
    }
}
