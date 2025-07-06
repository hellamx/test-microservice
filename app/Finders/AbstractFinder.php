<?php

namespace App\Finders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class AbstractFinder
{
    /**
     * @var string
     */
    protected string $model;

    /**
     * @var Builder
     */
    protected Builder $builder;

    /**
     * @var array
     */
    protected array $relationships = [];

    /**
     * @var Collection
     */
    protected Collection $rows;

    /**
     * @param Request $request
     * @return void
     */
    abstract protected function setSpecialConditions(Request $request): void;

    /**
     * @return array
     */
    abstract public function getMappedData(): array;

    /**
     * @param Request $request
     * @return void
     */
    protected function setCommonConditions(Request $request): void
    {
        if (!empty($request->get('id'))) {
            $this->builder->where('id', $request->get('id'));
        }

        if (!empty($request->get('project_name'))) {
            $this->builder->where('project_name', 'like', '%' . $request->get('project_name') . '%');
        }
    }

    /**
     * @param array $relations
     * @return void
     */
    public function setRelations(array $relations): void
    {
        $this->relationships = $relations;
    }

    /**
     * @param Collection $data
     * @return void
     */
    public function setRows(Collection $data): void
    {
        $this->rows = $data;
    }

    /**
     * @return Collection
     */
    public function getRows(): Collection
    {
        return $this->rows;
    }

    /**
     * @return array
     */
    protected function getRelations(): array
    {
        return $this->relationships;
    }

    /**
     * @return void
     */
    abstract public function setModel(): void;

    /**
     * @return string
     */
    protected function getModel(): string
    {
        return $this->model;
    }

    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function findByRequest(Request $request): LengthAwarePaginator
    {
        $this->setModel();

        $this->builder = app(
            $this->getModel()
        )->query();

        if (!empty($relations = $this->getRelations())) {
            $this->builder->with($relations);
        }

        $this->setCommonConditions($request);
        $this->setSpecialConditions($request);

        $data = $this->builder->paginate(
            $request->input('per_page') ?? 20,
        );

        $this->setRows(
            $data->getCollection()
        );

        return $data;
    }
}
