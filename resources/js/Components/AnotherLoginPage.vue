<template>
   <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALP School Club Login</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5; /* Light gray background */
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md flex flex-col items-center">
        <!-- Logo Section -->
        <div class="mb-8">
            <img src="http://googleusercontent.com/image_generation_content/1" alt="ALP School Club Logo" class="h-32 w-auto object-contain">
        </div>

        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Welcome Back!</h2>

        <!-- Login Form -->
        <form class="w-full space-y-6">
            <div>
                <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email Address</label>
                <input type="email" id="email" name="email" placeholder="your.email@example.com"
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 ease-in-out"
                       required>
            </div>

            <div>
                <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••"
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 ease-in-out"
                       required>
            </div>

            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center">
                    <input type="checkbox" id="remember_me" name="remember_me" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="remember_me" class="ml-2 text-gray-700">Remember me</label>
                </div>
                <a href="#" class="text-blue-600 hover:text-blue-800 font-medium transition duration-200 ease-in-out">Forgot Password?</a>
            </div>

            <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 rounded-lg font-semibold text-lg
                           hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50
                           transition duration-300 ease-in-out transform hover:scale-105 shadow-md hover:shadow-lg">
                Log In
            </button>
        </form>

        <div class="mt-6 text-center text-gray-600 text-sm">
            Don't have an account?
            <a href="#" class="text-blue-600 hover:text-blue-800 font-medium transition duration-200 ease-in-out">Sign Up</a>
        </div>
    </div>
</body>
</html>

</template>

<script lang="ts" setup>

</script>

<style>

</style>
