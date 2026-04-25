<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Badge;

class BadgesSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [

            // ============ HÁBITOS POR CATEGORÍA — DEPORTE ============
            ['name' => 'Aprendiz',  'description' => 'Tus primeros pasos en el deporte. El camino hacia la excelencia empieza aquí.',              'icon' => '🌱', 'rarity' => 'comun',      'category' => 'deporte',       'condition_type' => 'habit_category', 'condition_value' => 1],
            ['name' => 'Atleta',    'description' => 'Cuatro semanas de esfuerzo constante. Tu cuerpo empieza a notar la diferencia.',             'icon' => '🏃', 'rarity' => 'rara',       'category' => 'deporte',       'condition_type' => 'habit_category', 'condition_value' => 4],
            ['name' => 'Campeón',   'description' => 'Ocho semanas sin rendirte. Eres un ejemplo de disciplina y perseverancia.',                  'icon' => '🥇', 'rarity' => 'epica',      'category' => 'deporte',       'condition_type' => 'habit_category', 'condition_value' => 8],
            ['name' => 'Olímpico',  'description' => 'Dieciséis semanas de entrenamiento. Mereces subir al podio.',                                'icon' => '🏆', 'rarity' => 'legendaria', 'category' => 'deporte',       'condition_type' => 'habit_category', 'condition_value' => 16],

            // ============ HÁBITOS POR CATEGORÍA — LECTURA ============
            ['name' => 'Lector',      'description' => 'Abriste el primer libro. El conocimiento es el arma más poderosa.',                        'icon' => '📖', 'rarity' => 'comun',      'category' => 'lectura',       'condition_type' => 'habit_category', 'condition_value' => 1],
            ['name' => 'Bibliófilo',  'description' => 'Cuatro semanas entre páginas. Los libros ya son tus mejores compañeros.',                  'icon' => '📚', 'rarity' => 'rara',       'category' => 'lectura',       'condition_type' => 'habit_category', 'condition_value' => 4],
            ['name' => 'Erudito',     'description' => 'Ocho semanas de lectura constante. Tu mente se expande con cada página.',                  'icon' => '🎓', 'rarity' => 'epica',      'category' => 'lectura',       'condition_type' => 'habit_category', 'condition_value' => 8],
            ['name' => 'Sabio',       'description' => 'Dieciséis semanas absorbiendo conocimiento. La sabiduría es tu mayor tesoro.',             'icon' => '🦉', 'rarity' => 'legendaria', 'category' => 'lectura',       'condition_type' => 'habit_category', 'condition_value' => 16],

            // ============ HÁBITOS POR CATEGORÍA — MEDITACIÓN ============
            ['name' => 'Iniciado',   'description' => 'Tu primera semana en silencio interior. La mente empieza a calmarse.',                      'icon' => '🕯️', 'rarity' => 'comun',      'category' => 'meditacion',    'condition_type' => 'habit_category', 'condition_value' => 1],
            ['name' => 'Practicante','description' => 'Cuatro semanas cultivando la paz interior. El ruido del mundo ya no te afecta igual.',      'icon' => '🧘', 'rarity' => 'rara',       'category' => 'meditacion',    'condition_type' => 'habit_category', 'condition_value' => 4],
            ['name' => 'Iluminado',  'description' => 'Ocho semanas de práctica profunda. Has encontrado tu centro.',                              'icon' => '✨', 'rarity' => 'epica',      'category' => 'meditacion',    'condition_type' => 'habit_category', 'condition_value' => 8],
            ['name' => 'Asceta',     'description' => 'Dieciséis semanas de disciplina mental absoluta. Tu mente es un templo.',                   'icon' => '🌸', 'rarity' => 'legendaria', 'category' => 'meditacion',    'condition_type' => 'habit_category', 'condition_value' => 16],

            // ============ HÁBITOS POR CATEGORÍA — NUTRICIÓN ============
            ['name' => 'Comensal Iniciado', 'description' => 'Empiezas a tomar conciencia de lo que comes. El primer paso hacia una vida más sana.',    'icon' => '🥗', 'rarity' => 'comun',      'category' => 'nutricion',     'condition_type' => 'habit_category', 'condition_value' => 1],
            ['name' => 'Equilibrado',       'description' => 'Cuatro semanas cuidando tu alimentación. Tu cuerpo te lo agradece.',                      'icon' => '⚖️', 'rarity' => 'rara',       'category' => 'nutricion',     'condition_type' => 'habit_category', 'condition_value' => 4],
            ['name' => 'Saludable',         'description' => 'Ocho semanas de hábitos alimenticios sólidos. Eres lo que comes, y comes bien.',          'icon' => '🥦', 'rarity' => 'epica',      'category' => 'nutricion',     'condition_type' => 'habit_category', 'condition_value' => 8],
            ['name' => 'Nutricionista',     'description' => 'Dieciséis semanas de alimentación impecable. Podrías dar clases.',                        'icon' => '🍎', 'rarity' => 'legendaria', 'category' => 'nutricion',     'condition_type' => 'habit_category', 'condition_value' => 16],

            // ============ HÁBITOS POR CATEGORÍA — PRODUCTIVIDAD ============
            ['name' => 'Organizado',            'description' => 'Tu primera semana con todo bajo control. El caos empieza a ordenarse.',               'icon' => '📋', 'rarity' => 'comun',      'category' => 'productividad', 'condition_type' => 'habit_category', 'condition_value' => 1],
            ['name' => 'Eficiente',             'description' => 'Cuatro semanas optimizando tu tiempo. Haces más con menos.',                          'icon' => '⚡', 'rarity' => 'rara',       'category' => 'productividad', 'condition_type' => 'habit_category', 'condition_value' => 4],
            ['name' => 'Estratega',             'description' => 'Ocho semanas dominando tu agenda. Planificas como un general.',                       'icon' => '♟️', 'rarity' => 'epica',      'category' => 'productividad', 'condition_type' => 'habit_category', 'condition_value' => 8],
            ['name' => 'Arquitecto del Tiempo', 'description' => 'Dieciséis semanas de productividad máxima. El tiempo trabaja para ti.',               'icon' => '🏛️', 'rarity' => 'legendaria', 'category' => 'productividad', 'condition_type' => 'habit_category', 'condition_value' => 16],

            // ============ HÁBITOS POR CATEGORÍA — APRENDIZAJE ============
            ['name' => 'Curioso',      'description' => 'Tu primera semana explorando nuevos conocimientos. La curiosidad es el motor del progreso.',   'icon' => '🔍', 'rarity' => 'comun',      'category' => 'aprendizaje',   'condition_type' => 'habit_category', 'condition_value' => 1],
            ['name' => 'Estudioso',    'description' => 'Cuatro semanas aprendiendo sin parar. El conocimiento se acumula día a día.',                  'icon' => '📝', 'rarity' => 'rara',       'category' => 'aprendizaje',   'condition_type' => 'habit_category', 'condition_value' => 4],
            ['name' => 'Intelectual',  'description' => 'Ocho semanas de aprendizaje profundo. Tu mente no deja de crecer.',                            'icon' => '🧠', 'rarity' => 'epica',      'category' => 'aprendizaje',   'condition_type' => 'habit_category', 'condition_value' => 8],
            ['name' => 'Genio',        'description' => 'Dieciséis semanas absorbiendo todo lo que puedes. Einstein estaría orgulloso.',                'icon' => '💡', 'rarity' => 'legendaria', 'category' => 'aprendizaje',   'condition_type' => 'habit_category', 'condition_value' => 16],

            // ============ HÁBITOS POR CATEGORÍA — CREATIVIDAD ============
            ['name' => 'Aficionado', 'description' => 'Tus primeras creaciones. Todo gran artista empezó así.',                                        'icon' => '🎨', 'rarity' => 'comun',      'category' => 'creatividad',   'condition_type' => 'habit_category', 'condition_value' => 1],
            ['name' => 'Creador',    'description' => 'Cuatro semanas dando vida a tus ideas. La creatividad fluye en ti.',                             'icon' => '✏️', 'rarity' => 'rara',       'category' => 'creatividad',   'condition_type' => 'habit_category', 'condition_value' => 4],
            ['name' => 'Artista',    'description' => 'Ocho semanas creando sin parar. Tu obra empieza a tener identidad propia.',                      'icon' => '🖼️', 'rarity' => 'epica',      'category' => 'creatividad',   'condition_type' => 'habit_category', 'condition_value' => 8],
            ['name' => 'Visionario', 'description' => 'Dieciséis semanas de creación constante. Ves el mundo de una forma que pocos pueden imaginar.',  'icon' => '🌟', 'rarity' => 'legendaria', 'category' => 'creatividad',   'condition_type' => 'habit_category', 'condition_value' => 16],

            // ============ HÁBITOS POR CATEGORÍA — SUEÑO ============
            ['name' => 'Soñador',            'description' => 'Tu primera semana cuidando el descanso. El sueño es el combustible del éxito.',          'icon' => '🌙', 'rarity' => 'comun',      'category' => 'sueno',         'condition_type' => 'habit_category', 'condition_value' => 1],
            ['name' => 'Viajero Onírico',    'description' => 'Cuatro semanas durmiendo como mereces. Tus sueños son tu segundo mundo.',                'icon' => '💫', 'rarity' => 'rara',       'category' => 'sueno',         'condition_type' => 'habit_category', 'condition_value' => 4],
            ['name' => 'Guardián del Sueño', 'description' => 'Ocho semanas protegiendo tu descanso. Nada interrumpe tu paz nocturna.',                 'icon' => '🌌', 'rarity' => 'epica',      'category' => 'sueno',         'condition_type' => 'habit_category', 'condition_value' => 8],
            ['name' => 'Señor de los Sueños','description' => 'Dieciséis semanas de descanso perfecto. Dominas el arte de recuperarte.',                'icon' => '👁️', 'rarity' => 'legendaria', 'category' => 'sueno',         'condition_type' => 'habit_category', 'condition_value' => 16],

            // ============ HÁBITOS POR CATEGORÍA — SOCIAL ============
            ['name' => 'Sociable',              'description' => 'Tu primera semana cultivando relaciones. Las conexiones humanas son tu fuerza.',      'icon' => '👋', 'rarity' => 'comun',      'category' => 'social',        'condition_type' => 'habit_category', 'condition_value' => 1],
            ['name' => 'Amigo de tus amigos',   'description' => 'Cuatro semanas siendo el mejor versión de ti en tus relaciones.',                    'icon' => '🤝', 'rarity' => 'rara',       'category' => 'social',        'condition_type' => 'habit_category', 'condition_value' => 4],
            ['name' => 'Relaciones Públicas',   'description' => 'Ocho semanas construyendo puentes. Tu red social es tu mayor activo.',                'icon' => '🌐', 'rarity' => 'epica',      'category' => 'social',        'condition_type' => 'habit_category', 'condition_value' => 8],
            ['name' => 'Alma de la Comunidad',  'description' => 'Dieciséis semanas siendo el corazón de tu entorno. Todos quieren tenerte cerca.',    'icon' => '❤️', 'rarity' => 'legendaria', 'category' => 'social',        'condition_type' => 'habit_category', 'condition_value' => 16],

            // ============ HÁBITOS POR CATEGORÍA — FINANZAS ============
            ['name' => 'Ahorrador',   'description' => 'Tu primera semana controlando tus gastos. El primer euro ahorrado es el más difícil.',          'icon' => '🪙', 'rarity' => 'comun',      'category' => 'finanzas',      'condition_type' => 'habit_category', 'condition_value' => 1],
            ['name' => 'Inversor',    'description' => 'Cuatro semanas haciendo crecer tu dinero. El interés compuesto es tu aliado.',                  'icon' => '📈', 'rarity' => 'rara',       'category' => 'finanzas',      'condition_type' => 'habit_category', 'condition_value' => 4],
            ['name' => 'Financiero',  'description' => 'Ocho semanas con las finanzas bajo control. Tu futuro económico está asegurado.',               'icon' => '💼', 'rarity' => 'epica',      'category' => 'finanzas',      'condition_type' => 'habit_category', 'condition_value' => 8],
            ['name' => 'Magnate',     'description' => 'Dieciséis semanas de disciplina financiera impecable. Warren Buffett tomaría nota.',            'icon' => '💎', 'rarity' => 'legendaria', 'category' => 'finanzas',      'condition_type' => 'habit_category', 'condition_value' => 16],

            // ============ HÁBITOS POR CATEGORÍA — HOGAR ============
            ['name' => 'Detallista',     'description' => 'Tu primera semana cuidando cada rincón. Los pequeños detalles marcan la diferencia.',       'icon' => '🧹', 'rarity' => 'comun',      'category' => 'hogar',         'condition_type' => 'habit_category', 'condition_value' => 1],
            ['name' => 'Pulcro',         'description' => 'Cuatro semanas manteniendo el orden. Tu espacio refleja tu estado mental.',                  'icon' => '✨', 'rarity' => 'rara',       'category' => 'hogar',         'condition_type' => 'habit_category', 'condition_value' => 4],
            ['name' => 'Intendente',     'description' => 'Ocho semanas gestionando tu hogar con maestría. Todo en su sitio, siempre.',                'icon' => '🏠', 'rarity' => 'epica',      'category' => 'hogar',         'condition_type' => 'habit_category', 'condition_value' => 8],
            ['name' => 'Rey del Castillo','description' => 'Dieciséis semanas de hogar impecable. Tu casa es tu reino y está muy bien gobernado', 'icon' => '👑', 'rarity' => 'legendaria', 'category' => 'hogar',      'condition_type' => 'habit_category', 'condition_value' => 16],

            // ============ HÁBITOS POR CATEGORÍA — NATURALEZA ============
            ['name' => 'Excursionista',      'description' => 'Tu primera semana conectando con la naturaleza. El aire libre es tu medicina.',          'icon' => '🥾', 'rarity' => 'comun',      'category' => 'naturaleza',    'condition_type' => 'habit_category', 'condition_value' => 1],
            ['name' => 'Explorador',         'description' => 'Cuatro semanas descubriendo nuevos rincones. La naturaleza te llama y tú respondes.',   'icon' => '🧭', 'rarity' => 'rara',       'category' => 'naturaleza',    'condition_type' => 'habit_category', 'condition_value' => 4],
            ['name' => 'Superviviente',      'description' => 'Ocho semanas en contacto con la naturaleza. Podrías sobrevivir en el bosque.',           'icon' => '🌲', 'rarity' => 'epica',      'category' => 'naturaleza',    'condition_type' => 'habit_category', 'condition_value' => 8],
            ['name' => 'Hijo de la Naturaleza','description' => 'Dieciséis semanas fundido con el entorno natural. La tierra es tu hogar.',            'icon' => '🌍', 'rarity' => 'legendaria', 'category' => 'naturaleza',    'condition_type' => 'habit_category', 'condition_value' => 16],

            // ============ HÁBITOS DE HACER ============
            ['name' => 'Primer hábito', 'description' => 'Registraste tu primer cumplimiento. El hábito más difícil es el primero.',                   'icon' => '💡', 'rarity' => 'comun',      'category' => null, 'condition_type' => 'habit_hacer', 'condition_value' => 1],
            ['name' => 'Constante',     'description' => 'Treinta cumplimientos acumulados. La constancia es la clave del éxito.',                     'icon' => '🔄', 'rarity' => 'comun',      'category' => null, 'condition_type' => 'habit_hacer', 'condition_value' => 30],
            ['name' => 'Máquina',       'description' => 'Cien cumplimientos. Eres una máquina imparable que no conoce el descanso.',                  'icon' => '⚡', 'rarity' => 'rara',       'category' => null, 'condition_type' => 'habit_hacer', 'condition_value' => 100],
            ['name' => 'Imparable',     'description' => 'Trescientos sesenta y cinco cumplimientos. Un año entero de hábitos. Eres una leyenda.',     'icon' => '🏭', 'rarity' => 'epica',      'category' => null, 'condition_type' => 'habit_hacer', 'condition_value' => 365],

            // ============ HÁBITOS DE DEJAR ============
            ['name' => 'Por aquí se empieza', 'description' => 'Tu primer día sin caer. El primer día es el más importante de todos.',                 'icon' => '💪', 'rarity' => 'comun',      'category' => null, 'condition_type' => 'habit_dejar', 'condition_value' => 1],
            ['name' => 'Disciplinado',        'description' => 'Siete días resistiendo. Una semana entera demostrando de qué estás hecho.',            'icon' => '🧱', 'rarity' => 'comun',      'category' => null, 'condition_type' => 'habit_dejar', 'condition_value' => 7],
            ['name' => 'Inquebrantable',      'description' => 'Treinta días sin caer. Un mes entero de voluntad pura.',                               'icon' => '🔒', 'rarity' => 'rara',       'category' => null, 'condition_type' => 'habit_dejar', 'condition_value' => 30],
            ['name' => 'Voluntad de Hierro',  'description' => 'Noventa días resistiendo la tentación. Tu fuerza de voluntad es legendaria.',          'icon' => '🌊', 'rarity' => 'epica',      'category' => null, 'condition_type' => 'habit_dejar', 'condition_value' => 90],
            ['name' => 'Una persona nueva',   'description' => 'Trescientos sesenta y cinco días sin caer. Has reescrito tu historia por completo.',   'icon' => '👁️', 'rarity' => 'legendaria', 'category' => null, 'condition_type' => 'habit_dejar', 'condition_value' => 365],

            // ============ DIVERSIDAD ============
            ['name' => 'Equilibrista', 'description' => 'Hábitos activos en dos áreas distintas. Estás construyendo una vida equilibrada.',            'icon' => '🌍', 'rarity' => 'comun',      'category' => null, 'condition_type' => 'diversity', 'condition_value' => 2],
            ['name' => 'Polivalente',  'description' => 'Hábitos activos en cuatro áreas distintas. Tu crecimiento no tiene fronteras.',               'icon' => '⚖️', 'rarity' => 'rara',       'category' => null, 'condition_type' => 'diversity', 'condition_value' => 4],
            ['name' => 'Hábil en todo','description' => 'Hábitos activos en seis áreas distintas. Eres un maestro de la mejora personal.',            'icon' => '🎯', 'rarity' => 'epica',      'category' => null, 'condition_type' => 'diversity', 'condition_value' => 6],

            // ============ INTERESES PERSONALIZADOS ============
            ['name' => 'Explorador',       'description' => 'Añadiste tu primer interés personalizado. Eres dueño de tu propio camino.',              'icon' => '🗺️', 'rarity' => 'comun',      'category' => null, 'condition_type' => 'custom_interests', 'condition_value' => 1],
            ['name' => 'Pionero',          'description' => 'Tres intereses personalizados. Estás trazando un mapa que nadie ha dibujado antes.',     'icon' => '🧭', 'rarity' => 'rara',       'category' => null, 'condition_type' => 'custom_interests', 'condition_value' => 3],
            ['name' => 'Visionario Propio','description' => 'Cinco intereses personalizados activos. Has creado tu propia versión de LevlUp.',        'icon' => '🌌', 'rarity' => 'epica',      'category' => null, 'condition_type' => 'custom_interests', 'condition_value' => 5],

            // ============ TAREAS ============
            ['name' => 'Primer Paso',          'description' => 'Completaste tu primera misión. Todo gran viaje comienza con un primer paso.',        'icon' => '🎯', 'rarity' => 'comun',      'category' => null, 'condition_type' => 'tasks_completed', 'condition_value' => 1],
            ['name' => 'En Racha',             'description' => 'Diez misiones completadas. Estás cogiendo el ritmo, aventurero.',                    'icon' => '⚡', 'rarity' => 'comun',      'category' => null, 'condition_type' => 'tasks_completed', 'condition_value' => 10],
            ['name' => 'Imparable',            'description' => 'Cincuenta misiones completadas. Nada en tu lista de tareas te detiene.',             'icon' => '🏃', 'rarity' => 'rara',       'category' => null, 'condition_type' => 'tasks_completed', 'condition_value' => 50],
            ['name' => 'Maestro de Misiones',  'description' => 'Cien misiones completadas. Las misiones tiemblan cuando abres la app.',              'icon' => '👑', 'rarity' => 'epica',      'category' => null, 'condition_type' => 'tasks_completed', 'condition_value' => 100],
            ['name' => 'Leyenda de Misiones',  'description' => 'Quinientas misiones completadas. Tu lista de tareas merece estar en un museo.',      'icon' => '🌟', 'rarity' => 'legendaria', 'category' => null, 'condition_type' => 'tasks_completed', 'condition_value' => 500],

            // ============ RACHAS GLOBALES ============
            ['name' => 'Constante',   'description' => 'Siete días consecutivos de actividad. La constancia es tu superpoder.',                       'icon' => '🔥', 'rarity' => 'comun',      'category' => null, 'condition_type' => 'global_streak', 'condition_value' => 7],
            ['name' => 'Dedicado',    'description' => 'Treinta días consecutivos sin parar. Un mes entero de compromiso absoluto.',                   'icon' => '💥', 'rarity' => 'rara',       'category' => null, 'condition_type' => 'global_streak', 'condition_value' => 30],
            ['name' => 'Incansable',  'description' => 'Sesenta días consecutivos de actividad. Ni el cansancio puede contigo.',                      'icon' => '🦅', 'rarity' => 'epica',      'category' => null, 'condition_type' => 'global_streak', 'condition_value' => 60],
            ['name' => 'Eterno',      'description' => 'Noventa días consecutivos. Eres una fuerza de la naturaleza imparable.',                      'icon' => '⭐', 'rarity' => 'legendaria', 'category' => null, 'condition_type' => 'global_streak', 'condition_value' => 90],

            // ============ NIVELES ============
            ['name' => 'Recluta',           'description' => 'Alcanzaste el nivel 3. Tu aventura en LevlUp acaba de comenzar.',                       'icon' => '🎮', 'rarity' => 'comun',      'category' => null, 'condition_type' => 'level', 'condition_value' => 3],
            ['name' => 'Aventurero',        'description' => 'Alcanzaste el nivel 5. Ya no eres un novato, eres un verdadero aventurero.',            'icon' => '⚔️', 'rarity' => 'rara',       'category' => null, 'condition_type' => 'level', 'condition_value' => 5],
            ['name' => 'Héroe',             'description' => 'Alcanzaste el nivel 7. Tu dedicación te ha convertido en un héroe de LevlUp.',          'icon' => '🛡️', 'rarity' => 'epica',      'category' => null, 'condition_type' => 'level', 'condition_value' => 7],
            ['name' => 'Leyenda del Tiempo','description' => 'Nivel máximo alcanzado. Has trascendido los límites. Eres una leyenda para siempre.',   'icon' => '👑', 'rarity' => 'legendaria', 'category' => null, 'condition_type' => 'level', 'condition_value' => 10],

        ];

        foreach ($badges as $badge) {
            Badge::create($badge);
        }
    }
}