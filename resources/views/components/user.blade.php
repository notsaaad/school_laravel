<div class="user">
    <div class="title"> {{ trans('words.تفاصيل الحساب') }}</div>
    <div class="user_parant">
        <img src="{{ path($user->img) }}" alt="{{ $user->name }}">

        <div class="user_content">
            <div class="user_group">
                <div class="user_group_title"> {{ trans('words.name') }}</div>
                <div class="data">{{ $user->name }}</div>
            </div>
            <div class="user_group">
                <div class="user_group_title"> {{ trans('words.كود الطالب') }}</div>
                <div class="data">{{ $user->code }}</div>
            </div>
            <div class="user_group">
                <div class="user_group_title">{{ trans('words.النوع') }}</div>
                <div class="data">
                    @if ($user->gender == 'boy')
                        <td>{{ trans('words.ذكر') }}</td>
                    @else
                        <td>{{ trans('words.انثي') }}</td>
                    @endif
                </div>
            </div>

            <div class="user_group">
                <div class="user_group_title"> {{ trans('words.المرحلة') }} </div>
                <div class="data">{{ $user->stage->name }}</div>
            </div>
            <div class="user_group">
                <div class="user_group_title"> {{ trans('words.phone') }} </div>
                <div class="data">{{ $user->mobile }}</div>
            </div>
            <div class="user_group">
                <div class="user_group_title"> {{ trans('words.email') }} </div>
                <div class="data">{{ $user->email }}</div>
            </div>

        </div>
    </div>
</div>
