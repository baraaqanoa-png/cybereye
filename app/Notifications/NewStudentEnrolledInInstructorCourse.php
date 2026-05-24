<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewStudentEnrolledInInstructorCourse extends Notification
{
    use Queueable;

    protected $studentName;
    protected $studentId;
    protected $courseName;
    protected $courseId;

    /**
     * Create a new notification instance.
     */
    public function __construct($studentName, $studentId, $courseName, $courseId)
    {
        $this->studentName = $studentName;
        $this->studentId = $studentId;
        $this->courseName = $courseName;
        $this->courseId = $courseId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // أضف 'mail' هنا إذا أردت إرسال بريد إلكتروني أيضاً
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('📢 طالب جديد في كورسك')
            ->greeting('مرحباً ' . $notifiable->name)
            ->line("الطالب {$this->studentName} سجل في كورس '{$this->courseName}'.")
            ->action('عرض الكورس', url('/courses/' . $this->courseId))
            ->line('شكراً لاستخدامك منصتنا!');
    }

    /**
     * Get the array representation of the notification (للقاعدة database).
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => '📢 طالب جديد في كورسك',
            'message' => "الطالب {$this->studentName} سجل في كورس '{$this->courseName}'.",
            'type' => 'new_enrollment',
            'student_id' => $this->studentId,
            'student_name' => $this->studentName,
            'course_id' => $this->courseId,
            'course_name' => $this->courseName,
            'icon' => 'user-plus',
            'color' => 'green',
        ];
    }
}
