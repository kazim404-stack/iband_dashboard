<?php

namespace App\DataTables;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CategoriesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Category> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                return '<div class="d-flex justify-content-between ">
            <a href="' . route('admin.categories.edit', $query->id) . '"  class="btn btn-primary btn-md edit-category-btn" data-bs-toggle="modal" data-bs-target="#edit-category" style="margin-right:4px;"><i class="fas fa-edit"></i></a>
            <a href="' . route('admin.categories.destroy', $query->id) . '" class="btn btn-danger btn-md" id="confirmation" data-datatable_id="#categories-table"><i class="fas fa-trash" ></i></a>
            </div>';
            })->addColumn('name', function ($query) {
                return $query->getTranslation('name', 'en');
            })->addColumn('parent_category', function ($query) {
                return $query->parentCategory ? $query->parentCategory->getTranslation('name', 'en'): '0';
            })
            ->addColumn('status', function ($query) {
                return $query->status == 1 ? '<span class="badge bg-success text-white">Active</span>' : '<span class="badge bg-warning text-white">Inactive</span>';
            })->addColumn('image', function ($query) {
                return '<img class="img-thumbnail" src="' . asset($query->image) . '" alt="category-image-' . $query->id . '" width="100"/>';
            })->filterColumn('name', function ($query, $keyword) {
                $query->whereRaw(
                    "LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, '$.\"en\"'))) LIKE ?",
                    ["%" . strtolower($keyword) . "%"]
                );
            })
            ->rawColumns(['action', 'image', 'status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Category>
     */
    public function query(Category $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('categories-table')
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
            Column::make('parent_category'),
            Column::make('image'),
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
        return 'Categories_' . date('YmdHis');
    }
}
