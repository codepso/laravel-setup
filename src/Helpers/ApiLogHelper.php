<?php

namespace Codepso\Laravel\Helpers;

use Codepso\Laravel\Models\Log;
use Illuminate\Database\Eloquent\Model;

class ApiLogHelper
{
    /**
     * @param int $userId
     * @param string $action
     * @param string $modelName
     * @param Model $model
     * @param array $exceptions
     */
    public function crud(int $userId, string $action, string $modelName, Model $model, array $exceptions = [])
    {
        if (isset($model->id)) {
            if ($action !== 'updated') {
                $description = ucfirst($modelName) . " (id: $model->id) was $action";
                $snapshot = $action === 'deleted' ? $model->toJson() : null;
                $this->add($userId, $action, $modelName, $model->id, $description, $snapshot);
            } else {
                $this->modelUpdated($userId, $modelName, $model, $exceptions);
            }
        }
    }

    /**
     * @param int|null $userId
     * @param string $action
     * @param string|null $model
     * @param int|null $modelId
     * @param string|null $description
     * @param string|null $modelSnapshot
     */
    public function add(
        ?int $userId,
        string $action,
        ?string $model = null,
        ?int $modelId = null,
        ?string $description = null,
        ?string $modelSnapshot = null
    ) {
        $data = [
            'user_id' => $userId,
            'action' => $action
        ];

        if (!empty($model)) {
            $data['model'] = $model;
        }

        if (!empty($modelId)) {
            $data['model_id'] = $modelId;
        }

        if (!empty($description)) {
            $data['description'] = $description;
        }

        if (!empty($modelSnapshot)) {
            $data['model_snapshot'] = $modelSnapshot;
        }

        Log::create($data);
    }

    /**
     * @param int $userId
     * @param string $modelName
     * @param Model $model
     * @param array $exceptions
     */
    public function modelUpdated(int $userId, string $modelName, Model $model, array $exceptions = [])
    {
        $original = $model->getOriginal();
        $changes = $model->getChanges();
        $hidden = $model->getHidden();
        foreach (array_keys($changes) as $value) {
            if (!in_array($value, $hidden) && !in_array($value, $exceptions)) {
                $valAttr = $original[$value];
                $field = $this->tagField($value);
                $description = 'Field ' . $field . ' changed from “' . $valAttr . '” to “' . $changes[$value] . '”';
                if (isset($model->id)) {
                    $this->add($userId, 'updated', $modelName, $model->id, $description);
                }
            }
        }
    }

    /**
     * @param string $field
     * @return string
     */
    public function tagField(string $field): string
    {
        $tag = str_replace('_', ' ', $field);
        return ucfirst($tag);
    }
}
