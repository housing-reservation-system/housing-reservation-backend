<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Services\Shared\BookingService;
use App\Services\Shared\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    use ApiResponse, AuthorizesRequests;
    protected $bookingService;


    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index(Request $request)
    {
        $bookings = Booking::whereHas('apartment', function ($query) use ($request) {
            $query->where('user_id', Auth::user()->id);
        })
            ->with(['apartment', 'user'])
            ->latest()
            ->get();

        return $this->success(BookingResource::collection($bookings), __('Bookings retrieved successfully'));
    }

    public function approve(Booking $booking)
    {
        Gate::authorize('manage', $booking);

        $approvedBooking = $this->bookingService->approveBooking($booking);

 app(NotificationService::class)->sendSuccessNotification(
             $booking->apartment->host,
            'Booking approved successfully ',
            'your have approved the booking for apartment  ' . $booking->apartment->title .'from tenant' . $booking->user->name .'.'
            );
        return $this->success(new BookingResource($approvedBooking->load(['apartment', 'user'])), __('Booking approved successfully'));
    }

    public function reject(Request $request, Booking $booking)
    {
        Gate::authorize('manage', $booking);

        $rejectedBooking = $this->bookingService->rejectBooking($booking, $request->reason);
app(NotificationService::class)->sendSuccessNotification(
             $booking->apartment->host,
            'Booking Rejected',
            'your have rejected the booking  request for apartment  ' . $booking->apartment->title .'from tenant' . $booking->user->name .'.'
            );
        return $this->successMessage(__('Booking rejected successfully'));
    }
}
