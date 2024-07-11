<?php

/**
 *
 */

require_once './interface/BaseInterface.php';
require "./database/Database.php";

class TurfsModel extends Database
{
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Get all turfs
     *
     * @return array
     */
    public function get_all_turfs(): array
    {
        return $this->db->getAll('turf', [], []);
    }

    /**
     * Get turf by id
     *
     * @param int $id
     *
     * @return object | bool
     */
    public function get_turf(int $id): object | bool
    {
        return $this->db->get('turf', ['turf_id' => $id], []);
    }

    /**
     * Get turf images by turf id
     *
     * @param int $id
     *
     * @return array
     */
    public function get_turf_images(int $id): array
    {
        return $this->db->getAll('turf_images', ['turf_id' => $id], ['imageUrl']);
    }

    /**
    * Check if any of the requested time slots are already booked for the specified turf on the given date.
    *
    * @param int $turfId
    * @param string $turfDate
    * @param array $slotStarts Array of slot start times
    * @param array $slotEnds Array of slot end times
    * @return array List of conflicting time slots
    */
    public function getConflictingSlots(int $turfId, string $turfDate, array $slotStarts, array $slotEnds): array
    {
      $turfDate = date('Y-m-d', strtotime($turfDate));

      $conflictingSlots = [];

       foreach ($slotStarts as $index => $slotStart) {
         $slotEnd = $slotEnds[$index];
        
         $conditions = [
            'turf_id' => $turfId,
            'turf_date' => $turfDate,
            'slot_start <=' => $slotEnd,
            'slot_end >=' => $slotStart,
         ];

         $existingBookings = $this->db->getAll('turf_registrations', $conditions, ['slot_start', 'slot_end']);

          foreach ($existingBookings as $booking) {
            $bookedStart = $booking['slot_start'];
            $bookedEnd = $booking['slot_end'];

            if ($slotStart <= $bookedEnd && $slotEnd >= $bookedStart) {
                $conflictingSlots[] = [
                    'slot_start' => $bookedStart,
                    'slot_end' => $bookedEnd
                ];
            }
        }
    }

    return $conflictingSlots;
}
    /**
     * Book a user for a turf.
     *
     * @param array $details
     * @return bool
     */
    public function bookTurf(array $details): bool
    {
        return $this->db->insert('turf_registrations', $details);
    }
}