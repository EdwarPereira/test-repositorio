<?php

namespace App\Repository;

class ContactRepository
{
    public function findContactsOfCustomer($customerId, $search)
    {
        if (trim($search) != "") {
            $contacts = DB::table('contato_gt')
                ->where([['name', 'like', '%' . $search . '%'], ['customer_id', '=', $customerId], ['send_protocol', '=', 1]])
                ->select('contato_gt.*');
        } else {
            $contacts = DB::table('contato_gt')
                ->where([['customer_id', '=', $customerId], ['send_protocol', '=', 1]])
                ->select('contato_gt.*')
                ->get();
        }

        return $contacts;
    }
}