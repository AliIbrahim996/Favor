<?php


class Validation
{
    static function checUserEmptyData($data)
    {
        if (!empty($data->email) &&
            !empty($data->password) &&
            !empty($data->first_name) &&
            !empty($data->last_name) &&
            !empty($data->user_name) &&
            !empty($data->phone) &&
            !empty($data->userRole)) {
            return true;
        } else {
            return false;
        }
    }

    static function checkEmptyRestData($data)
    {
        if (!empty($data->name) &&
            !empty($data->location_lan) &&
            !empty($data->location_lat) &&
            !empty($data->details) &&
            !empty($data->owner_id) &&
            !empty($data->image)) {
            return true;
        } else {
            return false;
        }
    }

    static function checkEmptyMealData($data)
    {
        if (!empty($data->name) &&
            !empty($data->description) &&
            !empty($data->image) &&
            !empty($data->price) &&
            !empty($data->restaurant_id)) {
            return true;
        } else {
            return false;
        }
    }

    public static function checkEmptyReplayData($data)
    {
        if (!empty($data->comment_id) &&
            !empty($data->owner_id) &&
            !empty($data->user_id) &&
            !empty($data->replay)) {
            return true;
        } else {
            return false;
        }
    }

    public static function checkEmptyRateData($data)
    {
        if (!empty($data->content) &&
            !empty($data->value) &&
            !empty($data->time) &&
            !empty($data->user_id) &&
            !empty($data->owner_id) &&
            !empty($data->restaurant_id)) {
            return true;
        } else {
            return false;
        }
    }

    public static function checkEmptyCommentData($data)
    {
        if (!empty($data->content) &&
            !empty($data->time) &&
            !empty($data->user_id) &&
            !empty($data->restaurant_id)) {
            return true;
        } else {
            return false;
        }
    }

    public static function checkEmptyOrderData($data)
    {
        if (!empty($data->meal_id) &&
            !empty($data->time) &&
            !empty($data->quantity) &&
            !empty($data->date) &&
            !empty($data->user_id) &&
            !empty($data->restaurant_id)) {
            return true;
        } else {
            return false;
        }
    }
}