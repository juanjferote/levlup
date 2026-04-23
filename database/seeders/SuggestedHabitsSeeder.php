<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuggestedHabit;

class SuggestedHabitsSeeder extends Seeder
{
    public function run(): void
    {
        // catálogo de hábitos sugeridos por categoría y dificultad (1-5)
        // cada categoría tiene 5 hábitos de dificultad creciente
        $habits = [

            // ============ DEPORTE ============
            ['title' => 'Caminar 10 minutos',           'description' => 'Sal a caminar a paso ligero durante 10 minutos.',                'category' => 'deporte',    'difficulty_level' => 1, 'suggested_target_per_week' => 2, 'suggested_duration_minutes' => 10],
            ['title' => 'Correr 10 minutos',            'description' => 'Trote suave de 10 minutos para empezar a ganar resistencia.',    'category' => 'deporte',    'difficulty_level' => 2, 'suggested_target_per_week' => 3, 'suggested_duration_minutes' => 10],
            ['title' => 'Correr 20 minutos',            'description' => 'Carrera de 20 minutos a ritmo constante.',                       'category' => 'deporte',    'difficulty_level' => 3, 'suggested_target_per_week' => 3, 'suggested_duration_minutes' => 20],
            ['title' => 'Correr 30 minutos',            'description' => 'Carrera de 30 minutos, puedes alternar ritmos.',                 'category' => 'deporte',    'difficulty_level' => 4, 'suggested_target_per_week' => 4, 'suggested_duration_minutes' => 30],
            ['title' => 'Entrenamiento de fuerza',      'description' => 'Sesión de entrenamiento de fuerza de 45 minutos.',               'category' => 'deporte',    'difficulty_level' => 5, 'suggested_target_per_week' => 5, 'suggested_duration_minutes' => 45],

            // ============ LECTURA ============
            ['title' => 'Leer 5 páginas',               'description' => 'Lee al menos 5 páginas de cualquier libro que te interese.',     'category' => 'lectura',    'difficulty_level' => 1, 'suggested_target_per_week' => 2, 'suggested_duration_minutes' => 10],
            ['title' => 'Leer 15 minutos',              'description' => 'Dedica 15 minutos a la lectura sin distracciones.',              'category' => 'lectura',    'difficulty_level' => 2, 'suggested_target_per_week' => 3, 'suggested_duration_minutes' => 15],
            ['title' => 'Leer 30 minutos',              'description' => 'Sesión de lectura de 30 minutos al día.',                        'category' => 'lectura',    'difficulty_level' => 3, 'suggested_target_per_week' => 4, 'suggested_duration_minutes' => 30],
            ['title' => 'Leer un capítulo al día',      'description' => 'Termina un capítulo completo cada día.',                         'category' => 'lectura',    'difficulty_level' => 4, 'suggested_target_per_week' => 5, 'suggested_duration_minutes' => 40],
            ['title' => 'Leer 1 hora diaria',           'description' => 'Dedica una hora al día a la lectura profunda.',                  'category' => 'lectura',    'difficulty_level' => 5, 'suggested_target_per_week' => 6, 'suggested_duration_minutes' => 60],

            // ============ MEDITACIÓN ============
            ['title' => 'Respirar 2 minutos',           'description' => 'Haz 2 minutos de respiración consciente.',                       'category' => 'meditacion', 'difficulty_level' => 1, 'suggested_target_per_week' => 2, 'suggested_duration_minutes' => 2],
            ['title' => 'Meditar 5 minutos',            'description' => 'Sesión corta de meditación guiada o libre.',                     'category' => 'meditacion', 'difficulty_level' => 2, 'suggested_target_per_week' => 3, 'suggested_duration_minutes' => 5],
            ['title' => 'Meditar 10 minutos',           'description' => 'Meditación diaria de 10 minutos en un lugar tranquilo.',         'category' => 'meditacion', 'difficulty_level' => 3, 'suggested_target_per_week' => 4, 'suggested_duration_minutes' => 10],
            ['title' => 'Meditar 20 minutos',           'description' => 'Sesión de meditación profunda de 20 minutos.',                   'category' => 'meditacion', 'difficulty_level' => 4, 'suggested_target_per_week' => 5, 'suggested_duration_minutes' => 20],
            ['title' => 'Meditar mañana y noche',       'description' => 'Dos sesiones diarias de 15 minutos: al despertar y al dormir.',  'category' => 'meditacion', 'difficulty_level' => 5, 'suggested_target_per_week' => 6, 'suggested_duration_minutes' => 30],

            // ============ PRODUCTIVIDAD ============
            ['title' => 'Planificar el día',            'description' => 'Dedica 5 minutos a planificar las tareas del día.',              'category' => 'productividad', 'difficulty_level' => 1, 'suggested_target_per_week' => 3, 'suggested_duration_minutes' => 5],
            ['title' => 'Revisar objetivos semanales',  'description' => 'Revisa tus objetivos y ajusta si es necesario.',                 'category' => 'productividad', 'difficulty_level' => 2, 'suggested_target_per_week' => 3, 'suggested_duration_minutes' => 10],
            ['title' => 'Bloque de trabajo profundo',   'description' => 'Una sesión de 45 min sin interrupciones en tu tarea principal.', 'category' => 'productividad', 'difficulty_level' => 3, 'suggested_target_per_week' => 4, 'suggested_duration_minutes' => 45],
            ['title' => 'Dos bloques de foco',          'description' => 'Dos bloques de 45 min de trabajo profundo al día.',              'category' => 'productividad', 'difficulty_level' => 4, 'suggested_target_per_week' => 5, 'suggested_duration_minutes' => 90],
            ['title' => 'Revisión y mejora diaria',     'description' => 'Cada noche, revisa qué funcionó y qué mejorar mañana.',          'category' => 'productividad', 'difficulty_level' => 5, 'suggested_target_per_week' => 6, 'suggested_duration_minutes' => 15],

            // ============ APRENDIZAJE ============
            ['title' => 'Aprender 1 palabra nueva',     'description' => 'Aprende una palabra nueva en otro idioma o de tu profesión.',    'category' => 'aprendizaje', 'difficulty_level' => 1, 'suggested_target_per_week' => 3, 'suggested_duration_minutes' => 5],
            ['title' => 'Ver 1 vídeo educativo',        'description' => 'Mira un vídeo corto sobre algo que quieras aprender.',           'category' => 'aprendizaje', 'difficulty_level' => 2, 'suggested_target_per_week' => 3, 'suggested_duration_minutes' => 15],
            ['title' => 'Estudiar 30 minutos',          'description' => 'Sesión de estudio enfocado de 30 minutos.',                      'category' => 'aprendizaje', 'difficulty_level' => 3, 'suggested_target_per_week' => 4, 'suggested_duration_minutes' => 30],
            ['title' => 'Practicar una habilidad',      'description' => 'Dedica 45 minutos a practicar una habilidad concreta.',          'category' => 'aprendizaje', 'difficulty_level' => 4, 'suggested_target_per_week' => 5, 'suggested_duration_minutes' => 45],
            ['title' => 'Estudio diario 1 hora',        'description' => 'Una hora de estudio diario con apuntes y repaso.',               'category' => 'aprendizaje', 'difficulty_level' => 5, 'suggested_target_per_week' => 6, 'suggested_duration_minutes' => 60],

            // ============ NUTRICIÓN ============
            ['title' => 'Beber 1 litro de agua',        'description' => 'Bebe al menos 1 litro de agua a lo largo del día.',              'category' => 'nutricion', 'difficulty_level' => 1, 'suggested_target_per_week' => 3, 'suggested_duration_minutes' => null],
            ['title' => 'Comer fruta',                  'description' => 'Incluye al menos una pieza de fruta en tu día.',                 'category' => 'nutricion', 'difficulty_level' => 2, 'suggested_target_per_week' => 4, 'suggested_duration_minutes' => null],
            ['title' => 'Beber 2 litros de agua',       'description' => 'Bebe 2 litros de agua repartidos durante el día.',               'category' => 'nutricion', 'difficulty_level' => 3, 'suggested_target_per_week' => 5, 'suggested_duration_minutes' => null],
            ['title' => 'Comer 5 raciones veg/fruta',   'description' => 'Incluye 5 raciones de verdura o fruta al día.',                  'category' => 'nutricion', 'difficulty_level' => 4, 'suggested_target_per_week' => 5, 'suggested_duration_minutes' => null],
            ['title' => 'Registrar alimentación',       'description' => 'Apunta qué comes cada día para mejorar tus hábitos.',            'category' => 'nutricion', 'difficulty_level' => 5, 'suggested_target_per_week' => 7, 'suggested_duration_minutes' => 10],

            // ============ CREATIVIDAD ============
            ['title' => 'Anotar una idea',              'description' => 'Apunta al menos una idea creativa en tu cuaderno.',              'category' => 'creatividad', 'difficulty_level' => 1, 'suggested_target_per_week' => 2, 'suggested_duration_minutes' => 5],
            ['title' => 'Dibujar 10 minutos',           'description' => 'Haz un boceto rápido, sin exigencias.',                          'category' => 'creatividad', 'difficulty_level' => 2, 'suggested_target_per_week' => 3, 'suggested_duration_minutes' => 10],
            ['title' => 'Escribir 300 palabras',        'description' => 'Escribe un texto libre de al menos 300 palabras.',               'category' => 'creatividad', 'difficulty_level' => 3, 'suggested_target_per_week' => 3, 'suggested_duration_minutes' => 20],
            ['title' => 'Proyecto creativo 45 min',     'description' => 'Avanza en un proyecto personal durante 45 minutos.',             'category' => 'creatividad', 'difficulty_level' => 4, 'suggested_target_per_week' => 4, 'suggested_duration_minutes' => 45],
            ['title' => 'Crear algo cada día',          'description' => 'Produce algo creativo todos los días, aunque sea pequeño.',      'category' => 'creatividad', 'difficulty_level' => 5, 'suggested_target_per_week' => 7, 'suggested_duration_minutes' => 30],

            // ============ SUEÑO ============
            ['title' => 'Acostarse antes de las 00:00', 'description' => 'Estar en la cama antes de medianoche.',                          'category' => 'sueno', 'difficulty_level' => 1, 'suggested_target_per_week' => 3, 'suggested_duration_minutes' => null],
            ['title' => 'Sin pantallas 30 min antes',   'description' => 'Nada de pantallas 30 minutos antes de dormir.',                  'category' => 'sueno', 'difficulty_level' => 2, 'suggested_target_per_week' => 4, 'suggested_duration_minutes' => null],
            ['title' => 'Rutina de desconexión',        'description' => 'Haz una rutina relajante antes de dormir (lectura, música).',    'category' => 'sueno', 'difficulty_level' => 3, 'suggested_target_per_week' => 5, 'suggested_duration_minutes' => 20],
            ['title' => 'Dormir 7+ horas',              'description' => 'Duerme un mínimo de 7 horas cada noche.',                        'category' => 'sueno', 'difficulty_level' => 4, 'suggested_target_per_week' => 6, 'suggested_duration_minutes' => null],
            ['title' => 'Horario estricto de sueño',    'description' => 'Acuéstate y levántate siempre a la misma hora.',                 'category' => 'sueno', 'difficulty_level' => 5, 'suggested_target_per_week' => 7, 'suggested_duration_minutes' => null],

            // ============ SOCIAL ============
            ['title' => 'Mensaje a un ser querido',     'description' => 'Manda un mensaje a alguien importante para ti.',                 'category' => 'social', 'difficulty_level' => 1, 'suggested_target_per_week' => 2, 'suggested_duration_minutes' => 5],
            ['title' => 'Llamada semanal',              'description' => 'Llama a un familiar o amigo al menos una vez a la semana.',      'category' => 'social', 'difficulty_level' => 2, 'suggested_target_per_week' => 2, 'suggested_duration_minutes' => 15],
            ['title' => 'Quedada con amigos',           'description' => 'Queda en persona con amigos o familia.',                         'category' => 'social', 'difficulty_level' => 3, 'suggested_target_per_week' => 1, 'suggested_duration_minutes' => null],
            ['title' => 'Conocer a alguien nuevo',      'description' => 'Conversa con alguien nuevo esta semana.',                        'category' => 'social', 'difficulty_level' => 4, 'suggested_target_per_week' => 1, 'suggested_duration_minutes' => null],
            ['title' => 'Actividad grupal',             'description' => 'Participa en una actividad de grupo (club, equipo, taller).',    'category' => 'social', 'difficulty_level' => 5, 'suggested_target_per_week' => 2, 'suggested_duration_minutes' => null],

            // ============ FINANZAS ============
            ['title' => 'Anotar un gasto',              'description' => 'Registra al menos un gasto del día.',                            'category' => 'finanzas', 'difficulty_level' => 1, 'suggested_target_per_week' => 3, 'suggested_duration_minutes' => 2],
            ['title' => 'Revisar cuenta bancaria',      'description' => 'Revisa los movimientos de tu cuenta.',                           'category' => 'finanzas', 'difficulty_level' => 2, 'suggested_target_per_week' => 2, 'suggested_duration_minutes' => 5],
            ['title' => 'Registrar todos los gastos',   'description' => 'Apunta todos los gastos del día en una app o cuaderno.',         'category' => 'finanzas', 'difficulty_level' => 3, 'suggested_target_per_week' => 5, 'suggested_duration_minutes' => 5],
            ['title' => 'Presupuesto semanal',          'description' => 'Define un presupuesto semanal y compara al final.',              'category' => 'finanzas', 'difficulty_level' => 4, 'suggested_target_per_week' => 1, 'suggested_duration_minutes' => 20],
            ['title' => 'Ahorro automático',            'description' => 'Aparta un porcentaje de tus ingresos al ahorro cada semana.',    'category' => 'finanzas', 'difficulty_level' => 5, 'suggested_target_per_week' => 1, 'suggested_duration_minutes' => null],

            // ============ HOGAR ============
            ['title' => 'Hacer la cama',                'description' => 'Haz la cama al levantarte.',                                     'category' => 'hogar', 'difficulty_level' => 1, 'suggested_target_per_week' => 5, 'suggested_duration_minutes' => 2],
            ['title' => 'Recoger 10 minutos',           'description' => 'Dedica 10 minutos a recoger tu espacio.',                        'category' => 'hogar', 'difficulty_level' => 2, 'suggested_target_per_week' => 4, 'suggested_duration_minutes' => 10],
            ['title' => 'Limpieza de una zona',         'description' => 'Limpia a fondo una zona de tu casa esta semana.',                'category' => 'hogar', 'difficulty_level' => 3, 'suggested_target_per_week' => 2, 'suggested_duration_minutes' => 30],
            ['title' => 'Organizar y ordenar',          'description' => 'Organiza un cajón, estantería o armario.',                       'category' => 'hogar', 'difficulty_level' => 4, 'suggested_target_per_week' => 3, 'suggested_duration_minutes' => 20],
            ['title' => 'Rutina de limpieza diaria',    'description' => 'Mantén una rutina de limpieza y orden todos los días.',          'category' => 'hogar', 'difficulty_level' => 5, 'suggested_target_per_week' => 7, 'suggested_duration_minutes' => 20],

            // ============ NATURALEZA ============
            ['title' => 'Salir al aire libre',          'description' => 'Sal a la calle o a un espacio verde aunque sean 10 minutos.',    'category' => 'naturaleza', 'difficulty_level' => 1, 'suggested_target_per_week' => 2, 'suggested_duration_minutes' => 10],
            ['title' => 'Paseo por parque/bosque',      'description' => 'Da un paseo en un entorno natural.',                             'category' => 'naturaleza', 'difficulty_level' => 2, 'suggested_target_per_week' => 2, 'suggested_duration_minutes' => 30],
            ['title' => 'Excursión corta',              'description' => 'Haz una excursión o ruta de senderismo corta.',                  'category' => 'naturaleza', 'difficulty_level' => 3, 'suggested_target_per_week' => 1, 'suggested_duration_minutes' => 90],
            ['title' => 'Tiempo en naturaleza 2h',      'description' => 'Pasa al menos 2 horas a la semana en la naturaleza.',            'category' => 'naturaleza', 'difficulty_level' => 4, 'suggested_target_per_week' => 2, 'suggested_duration_minutes' => 60],
            ['title' => 'Ruta/excursión semanal',       'description' => 'Completa una ruta o excursión larga cada semana.',               'category' => 'naturaleza', 'difficulty_level' => 5, 'suggested_target_per_week' => 1, 'suggested_duration_minutes' => 180],
        ];

        // insertamos todos los hábitos sugeridos de una vez
        foreach ($habits as $habit) {
            SuggestedHabit::create($habit);
        }
    }
}