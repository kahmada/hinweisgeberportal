<div style="display: flex; gap: 4px; align-items: center;">
    <a href="{{ route('language.switch', 'de') }}"
       style="padding: 3px 8px; font-size: 12px; border-radius: 4px; text-decoration: none; border: 1px solid {{ app()->getLocale() === 'de' ? '#005FB8' : '#d1d5db' }}; color: {{ app()->getLocale() === 'de' ? '#005FB8' : '#6b7280' }}; font-weight: {{ app()->getLocale() === 'de' ? '600' : '400' }};">
        DE
    </a>
    <a href="{{ route('language.switch', 'en') }}"
       style="padding: 3px 8px; font-size: 12px; border-radius: 4px; text-decoration: none; border: 1px solid {{ app()->getLocale() === 'en' ? '#005FB8' : '#d1d5db' }}; color: {{ app()->getLocale() === 'en' ? '#005FB8' : '#6b7280' }}; font-weight: {{ app()->getLocale() === 'en' ? '600' : '400' }};">
        EN
    </a>
</div>
