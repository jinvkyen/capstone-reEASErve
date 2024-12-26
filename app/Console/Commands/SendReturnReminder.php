<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\UserReservationReminder;
use App\Mail\FacilityReservationReminder;

class SendReturnReminder extends Command
{
    protected $signature = 'reminder:send';
    protected $description = 'Send reminder emails for items and facilities that are due to be returned shortly';

    public function handle()
    {
        $now = Carbon::now();
        $tenMinutesFromNow = $now->copy()->addMinutes(10);
        $nineMinutesFromNow = $now->copy()->addMinutes(9);
        $oneMinuteFromNow = $now->copy()->addMinute();
        $twoMinutesFromNow = $now->copy()->addMinutes(2);

        // Reminder for User Reservation Requests
        $reservations = DB::table('user_reservation_requests')
            ->where(function ($query) use ($tenMinutesFromNow, $nineMinutesFromNow, $twoMinutesFromNow, $oneMinuteFromNow) {
                $query->whereBetween('return_datetime', [$nineMinutesFromNow, $tenMinutesFromNow])
                    ->orWhereBetween('return_datetime', [$oneMinuteFromNow, $twoMinutesFromNow]);
            })
            ->where('status', '=', 5) // Only for ongoing reservations
            ->get();

        foreach ($reservations as $reservation) {
            $user = DB::table('user_accounts')->where('user_id', $reservation->user_id)->first();
            $convertedTime = Carbon::parse($reservation->return_datetime)->format('g:i A');

            Mail::to($user->email)->send(new UserReservationReminder($user, $convertedTime));
        }

        // Reminder for Facility Reservations
        $facilityReservations = DB::table('facility_reservation')
            ->where(function ($query) use ($tenMinutesFromNow, $nineMinutesFromNow, $twoMinutesFromNow, $oneMinuteFromNow) {
                $query->whereBetween('end_datetime', [$nineMinutesFromNow, $tenMinutesFromNow])
                    ->orWhereBetween('end_datetime', [$oneMinuteFromNow, $twoMinutesFromNow]);
            })
            ->where('status', '=', 5) // Only for ongoing reservations
            ->get();

        foreach ($facilityReservations as $facilityReservation) {
            $user = DB::table('user_accounts')->where('user_id', $facilityReservation->user_id)->first();
            $convertedTime = Carbon::parse($facilityReservation->end_datetime)->format('g:i A');

            Mail::to($user->email)->send(new FacilityReservationReminder($user, $convertedTime));
        }
    }
}
