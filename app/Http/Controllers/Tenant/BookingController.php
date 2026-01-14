<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\ReviewBookingRequest;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Http\Requests\Booking\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Http\Resources\ReviewResource;
use App\Models\Booking;
use App\Services\Shared\BookingService;
use App\Services\ReviewService;
use App\Services\Shared\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Traits\ApiResponse;
use Exception;

class BookingController extends Controller
{
    use ApiResponse, AuthorizesRequests;
    protected $bookingService;
    protected $reviewService;
    public function __construct(BookingService $bookingService, ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
        $this->bookingService = $bookingService;
    }

    public function index(Request $request)
    {
        $bookings = $request->user()->bookings()
            ->with(['apartment', 'user'])
            ->get();

        return $this->success(BookingResource::collection($bookings), 'Bookings retrieved successfully');
    }

    public function store(StoreBookingRequest $request)
    {
        Gate::authorize('create', Booking::class);
        try {
            $booking = $this->bookingService->storeBooking($request->validated(), $request->user()->id);
            return $this->success(new BookingResource($booking->load(['apartment', 'user'])), __('Booking request submitted successfully'), 201);
              app(NotificationService::class)->sendSuccessNotification(
             $request->user(),
            'Booking request submitted  ',
            'your booking request for apartment ' . $booking->apartment->title . 'has been submitted successfully.'
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 400);
        }
    }

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        Gate::authorize('update', $booking);

        $updatedBooking = $this->bookingService->updateBooking($booking, $request->validated());
 app(NotificationService::class)->sendInfoNotification(
             $request->user(),
            'Modification request submitted  ',
            'your modification request for booking apartment ' . $booking->apartment->title . 'has been submitted .'
            );
        return $this->success(new BookingResource($updatedBooking->load(['apartment', 'user'])), __('Booking updated successfully. It now requires owner re-approval.'));
    }

    public function cancel(Request $request, Booking $booking)
    {
        try {
            Gate::authorize('delete', $booking);
            $this->bookingService->cancelBooking($booking, $request->reason);
             app(NotificationService::class)->sendWarningNotification(
             $request->user(),
            'Booking cancelled  ',
            'your booking  for apartment ' . $booking->apartment->title . 'has been cancelled successfully.'
            );
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 403);
        }
        return $this->successMessage('Booking cancelled successfully');
    }

    public function rate(ReviewBookingRequest $request, Booking $booking)
    {
        try {
            $review = $this->reviewService->storeReview([
                'booking_id' => $booking->id,
                'rating' => $request->rating,
                'feedback' => $request->feedback
            ], $request->user()->id);
 app(NotificationService::class)->sendSuccessNotification(
             $request->user(),
            'Review submitted  ',
            'thank you for reviewing  apartment ' . $booking->apartment->title . '.'
            );
            return $this->success(
                new ReviewResource($review),
                'Review submitted successfully',
                201
            );
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
}
