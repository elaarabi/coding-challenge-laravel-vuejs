<?php


namespace App\Services;


use App\Repositories\BaseRepository;

class BaseService
{

    protected BaseRepository $repository;
    /**
     * @var String Translation Category
     */
    protected string $trans;

    /**
     * Find Multiple Models
     *
     * @param array $data
     * @return array
     */
    public function getAll(array $data): array
    {
        try {
            return [
                'type' => 'success',
                'data' => $this->repository->all($data)
            ];
        } catch (\Exception $e) {
            return [
                'type' => 'error',
                'info' => $e->getMessage(),
                'code' => $e->getCode()
            ];
        }
    }

    /**
     * Get Model By ID
     * @param $id
     * @return array
     */
    public function getById($id): array
    {
        try {
            $model = $this->repository->find($id);

            // Check the product
            if (!$model) {
                return [
                    'type' => 'error',
                    'info' => trans("{$this->trans}.not_found", [
                        'id' => $id
                    ]),
                    'code' => 404
                ];
            }
            return [
                'type' => 'success',
                'data' => $model
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ];
        }
    }

    /**
     * Create Or Update Model
     * @param array $data
     * @param null $id
     * @return array
     */
    public function createOrUpdate(array $data, $id = null): array
    {
        $data = $this->prepareData($data);
        $validator = $this->repository->validator($data);
        if ($validator->fails()) {
            return [
                'type' => 'error',
                'info' => $validator->errors()->first(),
                'code' => 500
            ];
        }
        try {
            // If product exists when we find it
            // Then update the product
            // Else create the new one.
            $model = $id ? $this->repository->find($id) : $this->repository->getModel();

            // Check the product
            if ($id && !$model) {
                return [
                    'type' => 'error',
                    'info' => trans("{$this->trans}.not_found", [
                        'id' => $id
                    ]),
                    'code' => 404
                ];
            }
            if (!$this->repository->save()) {
                return [
                    'type' => 'error',
                    'info' => trans("{$this->trans}.cant_save"),
                    'code' => 500
                ];
            }
            $this->afterSave($data);
            return [
                'type' => 'success',
                'info' => trans("{$this->trans}." . ($id ? "updated" : "created"), [
                    'id' => $this->repository->getModel()->id
                ]),
                'data' => $this->repository->getModel(),
                'code' => $id ? 200 : 201
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getCode(),
                'code' => $e->getMessage()
            ];
        }
    }

    public function delete($id): array
    {
        try {
            $model = $this->repository->find($id);

            // Check the product
            if (!$model) {
                return [
                    'type' => 'error',
                    'info' => trans("{$this->trans}.not_found", [
                        'id' => $id
                    ]),
                    'code' => 404
                ];
            }

            // Delete the product
            if (!$this->repository->delete($id)) {
                return [
                    'type' => 'error',
                    'info' => trans("{$this->trans}.cant_remove"),
                    'code' => 500
                ];
            }
            return [
                'type' => 'success',
                'info' => trans("{$this->trans}.removed"),
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getCode(),
                'code' => $e->getMessage()
            ];
        }
    }

    /**
     * Prepare Data
     * @param array $data
     * @return array
     */
    public function prepareData(array $data): array
    {
        return $data;
    }

    /**
     * @param array $data
     */
    public function afterSave(array $data)
    {

    }
}
