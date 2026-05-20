<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Preferences</title>

    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-100 min-h-screen py-10">
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <form action="{{route('notifications.update')}}" method="post" class="space-y-6">
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div></div>

                        <div class="flex items-center">
                            @foreach($notificationChannels as $notificationChannel)
                                <div class="w-24 flex items-center justify-center font-semibold text-gray-800">
                                    {{ $notificationChannel->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @foreach($notificationTypes as $notificationType => $types)
                        <div class="border-b border-b-gray-100 last:border-b-0 pb-4">
                            <div class="flex items-center  justify-between">
                                <div class="text-lg font-semibold  text-gray-800">
                                    {{ ucfirst($notificationType) }}
                                </div>
                            </div>
                            <div class="space-y-2">
                                @foreach($types as $type)
                                    <div class="py-1 ">
                                        {{ $type->name }}
                                    </div>

                                    <div class="flex items-center">
                                        @foreach($notificationChannels as $notificationChannel)
                                            <div class="w-24 flex items-center justify-center">
                                                <input
                                                    type="checkbox"
                                                    class="rounded"
                                                    name="notifications[{{ $type->name }}][]"
                                                    value="{{ $notificationChannel->type }}"
                                                >
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                            @endforeach
                        </div>

                        <div class="flex items-center gap-4">
                            <button>{{ __('Save') }}</button>
                        </div>

                @csrf
                @method('patch')
            </form>
        </div>
    </div>
</div>
</body>
</html>
