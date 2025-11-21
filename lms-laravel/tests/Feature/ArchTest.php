test('globals')
    ->expect(['dd', 'dump', 'ray'])
    ->not->toBeUsed();

test('controllers')
    ->expect('App\Http\Controllers')
    ->not->toUse('Illuminate\Support\Facades\DB');
