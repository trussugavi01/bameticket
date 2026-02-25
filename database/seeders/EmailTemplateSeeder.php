<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Order Confirmation',
                'slug' => 'order-confirmation',
                'subject' => 'Your Order Confirmation for {event_title}',
                'body' => "Dear {buyer_name},\n\nThank you for your purchase! Your order for {event_title} has been successfully processed.\n\nWe are excited to see you at the National B.A.M.E Health & Care Awards ceremony. Your support helps us continue recognizing excellence across the healthcare sector.\n\n{order_summary_block}\n\nPlease find your digital tickets attached to this email or click the button below to download them directly to your device.\n\nBest regards,\nThe NBHCA Team",
                'type' => 'transactional',
                'available_variables' => ['buyer_name', 'event_title', 'order_id', 'ticket_count', 'order_summary_block'],
            ],
            [
                'name' => 'Refund Processed',
                'slug' => 'refund-processed',
                'subject' => 'Refund Confirmation - Order #{order_id}',
                'body' => "Dear {buyer_name},\n\nWe're writing to confirm that your refund request for Order #{order_id} has been processed.\n\nRefund Amount: {refund_amount}\nRefund Type: {refund_type}\nOriginal Order: {event_title}\n\nThe refund will appear in your account within 5-10 business days, depending on your payment provider.\n\nIf you have any questions, please don't hesitate to contact our support team.\n\nBest regards,\nThe NBHCA Team",
                'type' => 'transactional',
                'available_variables' => ['buyer_name', 'order_id', 'refund_amount', 'refund_type', 'event_title'],
            ],
            [
                'name' => 'Check-in Success',
                'slug' => 'checkin-success',
                'subject' => 'Welcome to {event_title}!',
                'body' => "Dear {attendee_name},\n\nYou have successfully checked in to {event_title}.\n\nCheck-in Time: {checkin_time}\nTicket Type: {ticket_type}\n\nWe hope you have a wonderful experience at the ceremony.\n\nBest regards,\nThe NBHCA Team",
                'type' => 'transactional',
                'available_variables' => ['attendee_name', 'event_title', 'checkin_time', 'ticket_type'],
            ],
            [
                'name' => 'Event Reminder',
                'slug' => 'event-reminder',
                'subject' => 'Reminder: {event_title} - {event_date}',
                'body' => "Dear {buyer_name},\n\nThis is a friendly reminder that {event_title} is coming up in {days_until_event} days!\n\nEvent Details:\nDate: {event_date}\nTime: {event_time}\nVenue: {venue_name}\nAddress: {venue_address}\n\nDress Code: {dress_code}\n\nPlease ensure you have your digital tickets ready for entry. We recommend arriving at least 30 minutes before doors open.\n\nWe look forward to seeing you there!\n\nBest regards,\nThe NBHCA Team",
                'type' => 'transactional',
                'available_variables' => ['buyer_name', 'event_title', 'event_date', 'event_time', 'venue_name', 'venue_address', 'dress_code', 'days_until_event'],
            ],
            [
                'name' => 'Waitlist Update',
                'slug' => 'waitlist-update',
                'subject' => 'Good News! Tickets Available for {event_title}',
                'body' => "Dear {buyer_name},\n\nGreat news! Tickets have become available for {event_title}.\n\nAs you were on our waitlist, we wanted to give you priority access to purchase tickets before they become available to the general public.\n\nThis offer is valid for the next 24 hours.\n\nClick the button below to secure your tickets now.\n\nBest regards,\nThe NBHCA Team",
                'type' => 'transactional',
                'available_variables' => ['buyer_name', 'event_title'],
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::firstOrCreate(
                ['slug' => $template['slug']],
                $template
            );
        }
    }
}
