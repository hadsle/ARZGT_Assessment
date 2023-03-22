@extends('profile.profile-layout')
@section('profile')
    <h1>User Activities</h1>

    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Activity Type</th>
                <th>Activity Date</th>
                <th>Edited Field</th>
                <th>Old Value</th>
                <th>New Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($activities as $activity)
                <tr>
                    <td>{{ $activity->user->name }}</td>
                    <td>{{ ucfirst($activity->activity_type) }}</td>
                    <td>{{ $activity->activity_date->format('Y-m-d H:i:s') }}</td>
                    <td>{{ array_key_first($activity->activity_details) }}</td>
                    <td>{{ current($activity->activity_details)['old_value'] }}</td>
                    <td>{{ current($activity->activity_details)['new_value'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
