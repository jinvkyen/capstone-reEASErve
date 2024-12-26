<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\SendFeedbackEmail;
use App\Mail\SendFeedbackEmailforLate;
use App\Mail\UserReservationReminder;
use App\Mail\FacilityReservationReminder;

class Notification_Controller extends Controller
{
    public function updateReservationStatus($transactionId, $newStatusId)
    {
        // Handle user_reservation_requests (for Laboratory and Equipment)
        $reservation = DB::table('user_reservation_requests')->where('transaction_id', $transactionId)->first();

        if ($reservation) {
            DB::table('user_reservation_requests')
                ->where('transaction_id', $transactionId)
                ->update(['status' => $newStatusId]);

            $status = DB::table('reservation_status')->where('status_id', $newStatusId)->first();

            if ($status && ($status->status_id == 5 || $status->status_id == 3 || $status->status_id == 4 || $status->status_id == 8)) {
                $this->sendUserReservationNotification($reservation, $newStatusId);
            }

            if ($status && in_array($status->status_id, [6, 9, 8])) { // Returned, Late, Cancelled
                $this->sendFeedbackEmail($reservation, 'equipment');
            }
        }

        // Handle facility_reservation (For Facilities)
        $facilityReservation = DB::table('facility_reservation')->where('id', $transactionId)->first();

        if ($facilityReservation) {
            DB::table('facility_reservation')
                ->where('id', $transactionId)
                ->update(['status' => $newStatusId]);

            $status = DB::table('reservation_status')->where('status_id', $newStatusId)->first();

            if ($status && ($status->status_id == 5 || $status->status_id == 3 || $status->status_id == 4 || $status->status_id == 8)) {
                $this->sendFacilityReservationNotification($facilityReservation, $newStatusId);
            }

            if ($status && in_array($status->status_id, [14, 9])) { // Completed
                $this->sendFacilityFeedbackEmail($facilityReservation);
            }
        }
    }

    public function sendUserReservationNotification($reservation, $newStatusId)
    {
        $user = DB::table('user_accounts')->where('user_id', $reservation->user_id)->first();

        $convertedEndTime = Carbon::parse($reservation->return_datetime)->format('M j, Y') . ' at ' . Carbon::parse($reservation->return_datetime)->format('g:i A');
        $convertedStartTime = Carbon::parse($reservation->pickup_datetime)->format('M j, Y') . ' at ' . Carbon::parse($reservation->pickup_datetime)->format('g:i A');

        Mail::to($user->email)->send(new UserReservationReminder($user, $convertedEndTime, $newStatusId, $convertedStartTime));
    }

    public function sendFacilityReservationNotification($facilityReservation, $newStatusId)
    {
        $user = DB::table('user_accounts')->where('user_id', $facilityReservation->user_id)->first();

        $convertedEndTime = Carbon::parse($facilityReservation->end_datetime)->format('M j, Y') . ' at ' . Carbon::parse($facilityReservation->end_datetime)->format('g:i A');
        $convertedStartTime = Carbon::parse($facilityReservation->start_datetime)->format('M j, Y') . ' at ' . Carbon::parse($facilityReservation->start_datetime)->format('g:i A');

        Mail::to($user->email)->send(new FacilityReservationReminder($user, $convertedEndTime, $newStatusId, $convertedStartTime));
    }

    public function sendFeedbackEmail($reservation, $type)
    {
        $user = DB::table('user_accounts')->where('user_id', $reservation->user_id)->first();

        // Determine the item or facility name and type based on the type
        if ($type === 'facility') {
            // Query facility name
            $facility = DB::table('facilities')->where('facilities_id', $reservation->facilities_id)->first();
            $itemOrFacility = $facility ? $facility->facility_name : 'Unknown Facility';
            $itemOrFacilityType = 'facility';
            $actionType = 'used';
        } else {
            // Query resource name and type
            $resource = DB::table('resources')->where('resource_id', $reservation->resource_id)->first();
            $resourceType = DB::table('resource_type')->where('category_id', $resource->resource_type)->first();
            $itemOrFacility = $resource ? $resource->resource_name : 'Unknown Resource';
            $itemOrFacilityType = $resourceType ? $resourceType->resource_type : 'Unknown Type';
            $actionType = 'borrowed';
        }

        // Construct the feedback link
        $feedbackLink = url('https://reeaserve.online/');

        $facilityLocation = $resource ? $resource->department_owner : 'Unknown Department Owner';

        // Send the email
        Mail::to($user->email)->send(new SendFeedbackEmail($user, $itemOrFacility, $itemOrFacilityType, $actionType, $feedbackLink, $facilityLocation));
    }

    public function sendFacilityFeedbackEmail($facilityReservation)
    {
        $user = DB::table('user_accounts')->where('user_id', $facilityReservation->user_id)->first();

        // Fetch the facility details including name and location
        $facility = DB::table('facilities')
            ->where('facilities_id', $facilityReservation->facility_name)
            ->first();

        // If the facility exists, use its name and location; otherwise, use 'Unknown Facility' and 'Unknown Location'
        $facilityName = $facility ? $facility->facility_name : 'Unknown Facility';
        $facilityLocation = $facility ? $facility->location : 'Unknown Department Location';

        $actionType = 'used';
        $feedbackLink = url('https://reeaserve.online/');

        // Pass the facility name and location to the email
        Mail::to($user->email)->send(new SendFeedbackEmail($user, $facilityName, 'facility', $actionType, $feedbackLink, $facilityLocation));
    }

    public function sendFeedbackEmailforLate($reservation, $type)
    {
        $user = DB::table('user_accounts')->where('user_id', $reservation->user_id)->first();

        $convertedEndTime = Carbon::parse($reservation->return_datetime)->format('M j, Y') . ' at ' . Carbon::parse($reservation->return_datetime)->format('g:i A');
        // Determine the item or facility name and type based on the type
        if ($type === 'facility') {
            // Query facility name
            $facility = DB::table('facilities')->where('facilities_id', $reservation->resource_id)->first();
            $itemOrFacility = $facility ? $facility->facility_name : 'Unknown Facility';
            $itemOrFacilityType = 'facility';
            $actionType = 'used';
        } else {
            // Query resource name and type
            $resource = DB::table('resources')->where('resource_id', $reservation->resource_id)->first();
            $resourceType = DB::table('resource_type')->where('category_id', $resource->resource_type)->first();
            $itemOrFacility = $resource ? $resource->resource_name : 'Unknown Resource';
            $itemOrFacilityType = $resourceType ? $resourceType->resource_type : 'Unknown Type';
            $actionType = 'borrowed';
        }

        // Construct the feedback link
        $feedbackLink = url('https://reeaserve.online/');

        // Send the email
        Mail::to($user->email)->send(new SendFeedbackEmailforLate($user, $itemOrFacility, $itemOrFacilityType, $actionType, $feedbackLink, $convertedEndTime));
    }

    protected function getStatusState($statusId)
    {
        $status = DB::table('reservation_status')->where('status_id', $statusId)->first();
        return $status ? $status->status_state : 'Unknown';
    }
}
