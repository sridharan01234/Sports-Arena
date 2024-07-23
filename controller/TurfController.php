<?php

require "BaseController.php";
require './model/TurfModel.php';

class TurfController extends BaseController
{
    private $turfModel;
    public function __construct()
    {
        $this->turfModel = new TurfModel();
    }

    public function getAllTurf()
    {
        $data = $this->decodeRequest();

        echo json_encode($this->turfModel->getAllTurf());
        exit;
    }

    public function getTurf()
    {
        $data = $this->decodeRequest();

        echo json_encode($this->turfModel->getTurf($_GET['id']));
        exit;
    }

    public function bookTurf()
    {
        $data = $this->decodeRequest();

        $details = [];
        $details['user_id'] = $_SESSION['user_id'];
        $details['turf_id'] = $data['id'];
        $details['turf_date'] = "2024-07-02";
        $details['slot_start'] = $data['slotStart'];
        $details['slot_end'] = $data['slotEnd'];
        $details['turf_location'] = $data['location'];
        $details['email'] = $data['email'];
        $details['player_name'] = $data['name'];

        $this->checkTiming($data['id'], $data['date'], $data['slotStart'], $data['slotEnd']);

        echo json_encode($this->turfModel->bookTurf($details));
        exit;
    }

    public function getTurfSlots()
    {
        $data = $this->decodeRequest();

        echo json_encode($this->turfModel->getTurfSlots($_GET['id'], $data['date']));
        exit;
    }

    private function checkTiming($id, $date, $start, $end)
    {
        $bookedTimings = $this->turfModel->getTurfSlots($id, $date);

        function periodsOverlap($start1, $end1, $start2, $end2)
        {
            return !($end1 <= $start2 || $end2 <= $start1);
        }

        $timingBlocked = false;
        foreach ($bookedTimings as $bookedTiming) {
            $start_time = $bookedTiming->slot_start;
            $end_time = $bookedTiming->slot_end;

            if (periodsOverlap($start_time, $end_time, $start, $end)) {
                $timingBlocked = true;
                break;
            }
        }

        if ($timingBlocked) {
            echo json_encode(
                [
                    "status" => "error",
                    "message" => "Request timing is nt available",
                ]
            );
            exit;
        }

    }
}
