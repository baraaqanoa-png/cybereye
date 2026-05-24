<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Database\Eloquent\Model;

class StudentNewLessonNotification extends Notification
{
    use Queueable;

    private $entity;

    /**
     * Create a new notification instance.
     */
    public function __construct(Model $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification for database storage.
     */
    public function toDatabase($notifiable)
    {
        $course = $this->entity->course;
        $lessonName = $this->entity->lesson_name ?? $this->entity->title ?? 'درس جديد';
        $courseName = $course?->course_name ?? $course?->title ?? 'كورس';
        $courseId = $course?->id;

        return [
            'type' => 'new_lesson',
            'lesson_id' => $this->entity->id,
            'lesson_name' => $lessonName,
            'course_id' => $courseId,
            'course_name' => $courseName,
            'title' => "درس جديد في: {$courseName}",
            'message' => 'تم إضافة درس جديد: ' . $lessonName,
            'icon' => 'fas fa-play-circle',
            'url' => $courseId ? route('video.index', $courseId) : null,
            'created_at' => now(),
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}
