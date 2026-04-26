<?php

namespace App\Http\Controllers;

use App\Services\NivelService;
use App\Services\TaskService;
use App\Services\HabitService;
use App\Services\BadgeService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private NivelService $nivelService,
        private TaskService  $taskService,
        private HabitService $habitService,
        private BadgeService $badgeService,
    ) {}

    public function index(): View
    {
        $user    = auth()->user();
        $grupos  = $this->taskService->tareasAgrupadas($user);
        $habitos = $this->habitService->habitosActivos($user);

        return view('dashboard.index', [
            ...$this->nivelService->datosProgreso($user),
            'racha'              => 0,
            'tareasHoy'          => $grupos['tareasHoy']->count(),
            'habitosHoy'         => $habitos['habitosHacer']->count() + $habitos['habitosDejar']->count(),
            'tareasHoyLista'     => $grupos['tareasHoy'],
            'habitosHacer'       => $this->habitService->conProgresoSemanal($habitos['habitosHacer']),
            'habitosDejar'       => $habitos['habitosDejar'],
            'insigniasRecientes' => $this->badgeService->insigniasRecientes($user),
            'fraseDelDia'        => $this->fraseDelDia(),
        ]);
    }
    private function fraseDelDia(): array
    {
        $frases = [
            ['texto' => 'La vida es lo que pasa mientras estás ocupado haciendo otros planes.', 'autor' => 'John Lennon'],
            ['texto' => 'El único modo de hacer un gran trabajo es amar lo que haces.', 'autor' => 'Steve Jobs'],
            ['texto' => 'En medio de la dificultad reside la oportunidad.', 'autor' => 'Albert Einstein'],
            ['texto' => 'El éxito es ir de fracaso en fracaso sin perder el entusiasmo.', 'autor' => 'Winston Churchill'],
            ['texto' => 'No cuentes los días, haz que los días cuenten.', 'autor' => 'Muhammad Ali'],
            ['texto' => 'La imaginación es más importante que el conocimiento.', 'autor' => 'Albert Einstein'],
            ['texto' => 'Sé el cambio que quieres ver en el mundo.', 'autor' => 'Mahatma Gandhi'],
            ['texto' => 'El camino de mil millas comienza con un solo paso.', 'autor' => 'Lao Tse'],
            ['texto' => 'La disciplina es el puente entre las metas y los logros.', 'autor' => 'Jim Rohn'],
            ['texto' => 'No es la especie más fuerte la que sobrevive, sino la que mejor se adapta al cambio.', 'autor' => 'Charles Darwin'],
            ['texto' => 'Primero, resuelve el problema. Luego, escribe el código.', 'autor' => 'John Johnson'],
            ['texto' => 'La creatividad es la inteligencia divirtiéndose.', 'autor' => 'Albert Einstein'],
            ['texto' => 'El que no arriesga no gana.', 'autor' => 'Napoleón Bonaparte'],
            ['texto' => 'La perseverancia es la madre de todas las virtudes.', 'autor' => 'Johann Wolfgang von Goethe'],
            ['texto' => 'Whoever is happy will make others happy too.', 'autor' => 'Anne Frank'],
            ['texto' => 'No llores porque terminó, sonríe porque sucedió.', 'autor' => 'Gabriel García Márquez'],
            ['texto' => 'La vida no se mide por las veces que respiras, sino por los momentos que te dejan sin aliento.', 'autor' => 'Maya Angelou'],
            ['texto' => 'El único lugar donde el éxito viene antes que el trabajo es en el diccionario.', 'autor' => 'Vidal Sassoon'],
            ['texto' => 'Lo que no te mata te hace más fuerte.', 'autor' => 'Friedrich Nietzsche'],
            ['texto' => 'La felicidad no es algo hecho. Viene de tus propias acciones.', 'autor' => 'Dalai Lama'],
            ['texto' => 'El talento gana partidos, pero el trabajo en equipo gana campeonatos.', 'autor' => 'Michael Jordan'],
            ['texto' => 'La educación es el arma más poderosa que puedes usar para cambiar el mundo.', 'autor' => 'Nelson Mandela'],
            ['texto' => 'No importa lo lento que vayas, siempre y cuando no te detengas.', 'autor' => 'Confucio'],
            ['texto' => 'Nunca es demasiado tarde para ser lo que podrías haber sido.', 'autor' => 'George Eliot'],
            ['texto' => 'La motivación te pone en marcha, el hábito te mantiene en movimiento.', 'autor' => 'Jim Ryun'],
            ['texto' => 'El éxito generalmente llega a quienes están demasiado ocupados para buscarlo.', 'autor' => 'Henry David Thoreau'],
            ['texto' => 'Haz de cada día tu obra maestra.', 'autor' => 'John Wooden'],
            ['texto' => 'La única forma de hacer un trabajo excelente es amar lo que haces.', 'autor' => 'Steve Jobs'],
            ['texto' => 'El futuro pertenece a quienes creen en la belleza de sus sueños.', 'autor' => 'Eleanor Roosevelt'],
            ['texto' => 'No hay nada imposible para el que lo intenta.', 'autor' => 'Alejandro Magno'],
        ];

        // usamos la fecha como semilla para que la frase sea la misma todo el día
        srand(intval(now()->format('Ymd')));
        $indice = rand(0, count($frases) - 1);
        srand(); // restauramos la semilla aleatoria para no afectar a otras partes del código

        return $frases[$indice];
    }
}
