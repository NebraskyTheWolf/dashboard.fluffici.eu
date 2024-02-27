<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * Fetches events from the calendar.
     *
     * @param Request $request The request object containing the calendarId, from, and to parameters.
     *    - calendarId (string) - The ID of the calendar to fetch events from.
     *    - from (string|Carbon|null) - The start date/time of the events to fetch. Defaults to current date/time if not provided.
     *    - to (string|Carbon|null) - The end date/time of the events to fetch. Defaults to 30 days after the current date/time if not provided.
     *
     * @return JsonResponse The JSON response containing the fetched events.
     *    - status (bool) - Indicates if the event fetch was successful.
     *    - data (Collection) - The collection of fetched events.
     */
    public function fetchEvents(Request $request): JsonResponse
    {
        $calendarId = $request->query('calendarId');

        if ($calendarId === NULL) {
            return response()->json([
                'status' => false,
                'error' => "INVALID_ID",
                'message' => 'The calendarId is invalid.'
            ]);
        }

        $check = CalendarEvent::where('calendar_id', $calendarId)->exists();
        if (!$check) {
            return response()->json([
                'status' => false,
                'message' => 'No calendarId found'
            ]);
        }

        $from = $request->query('from') !== null ? $request->query('from') : Carbon::now();
        $to = $request->query('to') !== null ? $request->query('to') : Carbon::now()->addDays(30);
        $limit = $request->query('limit') !== null ? intval($request->query('to')) : 10000;

        if ($limit <= 0) {
            return response()->json([
                'status' => false,
                'message' => 'No event(s) found'
            ]);
        }

        $events = CalendarEvent::where('calendar_id', $calendarId)->whereBetween('created_at', [
            $from,
            $to
        ])->orderBy('created_at', 'desc')->limit($limit)->paginate();

        return response()->json([
            'status' => true,
            'data' => $events
        ]);
    }

    /**
     * Adds a new event to the calendar.
     *
     * @param Request $request The request object containing the calendarId, title, start, and end of the new event.
     *
     * @return JsonResponse The JSON response indicating the status of the event addition.
     *                    Returns JSON with 'status' set to true, 'message' set to 'Event added successfully', and 'data' set to the newly created event object if the event is successfully
     * added.
     */
    public function addEvent(Request $request): JsonResponse
    {
        $calendarId = $request->query('calendarId');

        if ($calendarId === NULL) {
            return response()->json([
                'status' => false,
                'error' => "INVALID_ID",
                'message' => 'The calendarId is invalid.'
            ]);
        }

        $data = json_decode(json_encode($request->all()), true);

        $newEvent = new CalendarEvent();
        $newEvent->calendar_id = $calendarId;

        $newEvent->fill($data);
        $newEvent->save();

        return response()->json([
            'status' => true,
            'message' => 'Event added successfully',
            'data' => $newEvent
        ]);
    }

    /**
     * Update an event in the calendar.
     *
     * @param Request $request The HTTP request object containing the update data.
     *  - query parameter 'calendarId': The ID of the calendar.
     *  - JSON payload: The updated event data as an associative array.
     *      Required fields:
     *      - 'eventId': The ID of the event to update.
     *      Optional fields:
     *      - Any other fields to update in the event.
     *
     * @return JsonResponse The JSON response containing the status
     *  of the update operation and the updated event. If the event is found and
     *  updated, the response will have 'status' => true, 'message' =>
     *  'Event updated successfully', and 'data' => $event. If the event is not found,
     *  the response will have 'status' => false and 'message' => 'Event not found'.
     */
    public function updateEvent(Request $request): JsonResponse
    {
        $calendarId = $request->query('calendarId');

        if ($calendarId === NULL) {
            return response()->json([
                'status' => false,
                'error' => "INVALID_ID",
                'message' => 'The calendarId is invalid.'
            ]);
        }

        $data = json_decode(json_encode($request->all()), true);

        $eventId = $data['eventId'];
        $event = CalendarEvent::where('calendar_id', $calendarId)->where('id', $eventId)->first();
        if ($event) {
            $event->update($data);
            return response()->json([
                'status' => true,
                'message' => 'Event updated successfully',
                'data' => $event
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Event not found'
            ]);
        }
    }

    /**
     * Removes an event from the calendar.
     *
     * @param Request $request The request object containing the calendarId and eventId.
     *
     * @return JsonResponse The JSON response indicating the status of the event removal.
     *                    Returns JSON with 'status' set to true and 'message' set to 'Event removed successfully' if the event is found and successfully removed.
     *                    Returns JSON with 'status' set to false and 'message' set to 'Event not found' if the event is not found.
     */
    public function removeEvent(Request $request): JsonResponse
    {
        $calendarId = $request->query('calendarId');

        if ($calendarId === NULL) {
            return response()->json([
                'status' => false,
                'error' => "INVALID_ID",
                'message' => 'The calendarId is invalid.'
            ]);
        }

        $data = json_decode(json_encode($request->all()), true);

        $eventId = $data['eventId'];
        $event = CalendarEvent::where('calendar_id', $calendarId)->where('id', $eventId);
        if ($event->exists()) {
            $event->delete();
            return response()->json([
                'status' => true,
                'message' => 'Event removed successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Event not found'
            ]);
        }
    }
}
