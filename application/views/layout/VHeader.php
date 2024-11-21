<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Sử dụng cdn để nhúng jquery -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script> -->
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!--  -->
    <link rel="stylesheet" href="{$base_url}application/assets/toastr/toastr.min.css">
    <title> {$title} </title>
    
</head>

<body class="bg-blue-100 pb-16">
    <div class="">
        <div id="header" class="border-2 bg-opacity-30 bg-green-500 p-5">
            <h1 class="flex justify-center text-xl"> Xin chào đến với Hoàng Anh Channel! </h1>
        </div>

        <div id="content" class="grid grid-col-1 sm:grid-cols-1 border-gray-900/10">
              
            <!-- Bộ office -->
                <div id="office" class="mt-5 mx-10 flex justify-start">
                </div>

    