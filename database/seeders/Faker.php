<?php

class Faker
{
    private static array $firstNames = [

        "Rahul","Amit","Akash","Rohit","Mayur",
        "Vishal","Karan","Sachin","Neha","Priya",
        "Sneha","Anjali","Pooja","Ritika","Abhishek",
        "Sagar","Ajay","Vikas","Nikhil","Siddharth"

    ];

    private static array $lastNames = [

        "Sharma","Patel","Verma","Gupta",
        "Kulkarni","Joshi","Mehta","Singh",
        "Yadav","Pawar","Patil","More",
        "Jadhav","Deshmukh","Chavan"

    ];

    public static function fullName()
    {
        return self::$firstNames[array_rand(self::$firstNames)]
            ." ".
            self::$lastNames[array_rand(self::$lastNames)];
    }

    public static function mobile()
    {
        return "9".rand(100000000,999999999);
    }

    public static function email($name)
    {
        $email = strtolower(str_replace(" ",".",$name));

        return $email."@company.local";
    }

    public static function randomDate($start,$end)
    {
        return date(

            "Y-m-d",

            mt_rand(

                strtotime($start),
                strtotime($end)

            )

        );
    }
}