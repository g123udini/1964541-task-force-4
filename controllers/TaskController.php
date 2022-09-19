<?php

namespace app\controllers;

use app\components\AccessControllers\SecuredController;
use app\models\forms\AddTaskForm;
use app\models\forms\FilterForm;
use app\models\forms\ResponseForm;
use app\models\forms\ReviewForm;
use app\models\Response;
use app\models\Review;
use app\models\Task;
use TaskForce\actions\ActionAccept;
use TaskForce\actions\ActionApprove;
use TaskForce\actions\ActionCancel;
use TaskForce\actions\ActionExecute;
use TaskForce\actions\ActionRefuse;
use TaskForce\actions\ActionReject;
use TaskForce\exceptions\ModelSaveException;
use TaskForce\TaskService;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class TaskController extends SecuredController
{
    public function actionIndex()
    {
        $filterForm = new FilterForm();
        $tasks = $filterForm->getTasksQuery()->all();

        if (Yii::$app->request->getIsPost()) {
            $filterForm->load(Yii::$app->request->post());
            if (!$filterForm->validate()) {
                $errors = $this->getErrors();
            } else {
                $tasks = $filterForm->getFilteredTasks();
            }
        }

        return $this->render('task', ['tasks' => $tasks, 'model' => $filterForm]);
    }

    public function actionView($id)
    {
        $task = Task::findOne($id);
        $responseForm = new ResponseForm();
        $reviewForm = new ReviewForm();

        if (!$task) {
            throw new NotFoundHttpException("Задание с ID $id не найден");
        }
        return $this->render('view', ['task' => $task, 'responseForm' => $responseForm, 'reviewForm' => $reviewForm]);
    }

    public function actionAdd()
    {
        $addTaskForm = new AddTaskForm();
        if (Yii::$app->request->getIsPost()) {
            $addTaskForm->load(Yii::$app->request->post());
            $addTaskForm->file = UploadedFile::getInstance($addTaskForm, 'file');
            if ($addTaskForm->validate()) {
                if (!$addTaskForm->loadToTask()->save()) {
                    throw new ModelSaveException('Не удалось сохранить данные');
                }
                return $this->goHome();
            }
        }

        return $this->render('add', ['model' => $addTaskForm]);
    }

    public function actionApprove($id, $response_id)
    {
        $taskService = new TaskService($id);
        $taskService->actionApprove($response_id, Yii::$app->user->id);
        $taskService->saveTransaction();

        return Yii::$app->response->redirect(['task/view', 'id' => $id]);
    }

    public function actionReject($id)
    {
        $taskService = new TaskService($id);
        $taskService->actionReject(Yii::$app->user->id);
        $taskService->saveTaskChanges();

        return $this->goHome();
    }

    public function actionResponse()
    {
        $responseForm = new ResponseForm();
        $responseForm->load(Yii::$app->request->post());

        if ($responseForm->validate()) {
            $taskService = new TaskService($responseForm->taskId);
            $taskService->actionResponse(Yii::$app->user->id, $responseForm);
            $taskService->saveAnswerChanges();

            return Yii::$app->response->redirect(['task/view', 'id' => $responseForm->taskId]);
        }
    }

    public function actionReview()
    {
        $reviewForm = new ReviewForm();
        $reviewForm->load(Yii::$app->request->post());

        if ($reviewForm->validate()) {
            $taskService = new TaskService($reviewForm->taskId);
            $taskService->actionReview(Yii::$app->user->id, $reviewForm);
            $taskService->saveTransaction();

            return Yii::$app->response->redirect(['task']);
        }
    }

    public function actionRefuse($id, $response_id)
    {
        $taskService = new TaskService($id);
        $taskService->actionRefuse($response_id, Yii::$app->user->id);
        $taskService->saveAnswerChanges();

        return Yii::$app->response->redirect(['task/view', 'id' => $id]);
    }

    public function actionCancel($id)
    {
        $taskService = new TaskService($id);
        $taskService->actionCancel(Yii::$app->user->id);
        $taskService->saveTaskChanges();

        return $this->goHome();
    }
}