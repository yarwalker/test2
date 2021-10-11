<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\MessageStoreRequest;
use App\Http\Resources\EmailMessageCollection;
use App\Models\EmailMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;

/**
 * Класс для работы с сообщениями из формы обратной связи
 */
class MailController extends Controller
{
    private $perPage = 10;

    /**
     * Возвращает коллекцию объектов сообщений с возможной сортировкой по дате создания и
     * пагинацией (10 записей на странице)
     * в формате JSON
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $collection = EmailMessage::all();
        if (request()->has('sort')) {
            switch (request()->query('sort')) {
                case 'createdAt':
                    $collection = EmailMessage::select(['name', 'email', 'created_at'])->orderBy('created_at')->get();
                    break;
                case '-createdAt':
                    $collection = EmailMessage::select(['name', 'email', 'created_at'])->orderByDesc('created_at')->get();
                    break;
            }
        }

        $email_messages = new EmailMessageCollection($collection->forPage(request()->query('page') ?? 1, $this->perPage));
        return response()->json(compact('email_messages'));
    }

    /**
     * Создает и сохраняет запись сообщения в храгилище
     * Возвращает ID созданной записи и статус true,
     * или сообщений об ошибке и статус false
     * в формате JSON
     *
     * @param MessageStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(MessageStoreRequest $request)
    {
        $data = $request->validated();

        try {
            $message = EmailMessage::create($data);
        } catch (QueryException $e) {
            return response()->json(['error' => $e->getMessage(), 'result' => false]);
        }

        return response()->json(['id' => $message->id, 'result' => true]);
    }

    /**
     * Возвращает экземпляр сообщения по ID
     * в формате JSON
     *
     * @param EmailMessage $emailMessage
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(EmailMessage $emailMessage)
    {
        return response()->json(compact('emailMessage'));
    }
}
