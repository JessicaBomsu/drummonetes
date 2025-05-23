const pianoNotes = ['A0', 'A0#', 'B0', 'C1', 'C1#', 'D1', 'D1#', 'E1', 'F1', 'F1#', 'G1', 'G1#',
    'A1', 'A1#', 'B1', 'C2', 'C2#', 'D2', 'D2#', 'E2', 'F2', 'F2#', 'G2', 'G2#',
    'A2', 'A2#', 'B2', 'C3', 'C3#', 'D3', 'D3#', 'E3', 'F3', 'F3#', 'G3', 'G3#',
    'A3', 'A3#', 'B3', 'C4', 'C4#', 'D4', 'D4#', 'E4', 'F4', 'F4#', 'G4', 'G4#',
    'A4', 'A4#', 'B4', 'C5', 'C5#', 'D5', 'D5#', 'E5', 'F5', 'F5#', 'G5', 'G5#',
    'A5', 'A5#', 'B5', 'C6', 'C6#', 'D6', 'D6#', 'E6', 'F6', 'F6#', 'G6', 'G6#',
    'A6', 'A6#', 'B6', 'C7', 'C7#', 'D7', 'D7#', 'E7', 'F7', 'F7#', 'G7', 'G7#',
    'A7', 'A7#', 'B7', 'C8'];

const whiteNotes = ['A0', 'B0', 'C1', 'D1', 'E1', 'F1', 'G1',
    'A1', 'B1', 'C2', 'D2', 'E2', 'F2', 'G2',
    'A2', 'B2', 'C3', 'D3', 'E3', 'F3', 'G3',
    'A3', 'B3', 'C4', 'D4', 'E4', 'F4', 'G4',
    'A4', 'B4', 'C5', 'D5', 'E5', 'F5', 'G5',
    'A5', 'B5', 'C6', 'D6', 'E6', 'F6', 'G6',
    'A6', 'B6', 'C7', 'D7', 'E7', 'F7', 'G7',
    'A7', 'B7', 'C8'];

// Para nomes de arquivos, é comum usar 's' para sustenido (sharp) no lugar de '#'
// e 'b' para bemol (flat) já está ok.
// Ex: C# -> Cs, Db -> Db
const blackNotes = ['Bb0', 'Db1', 'Eb1', 'Gb1', 'Ab1',
    'Bb1', 'Db2', 'Eb2', 'Gb2', 'Ab2',
    'Bb2', 'Db3', 'Eb3', 'Gb3', 'Ab3',
    'Bb3', 'Db4', 'Eb4', 'Gb4', 'Ab4',
    'Bb4', 'Db5', 'Eb5', 'Gb5', 'Ab5',
    'Bb5', 'Db6', 'Eb6', 'Gb6', 'Ab6',
    'Bb6', 'Db7', 'Eb7', 'Gb7', 'Ab7',
    'Bb7'];

// Rótulos para exibição nas teclas pretas
const blackLabels = ['A#0', 'C#1', 'D#1', 'F#1', 'G#1',
    'A#1', 'C#2', 'D#2', 'F#2', 'G#2',
    'A#2', 'C#3', 'D#3', 'F#3', 'G#3',
    'A#3', 'C#4', 'D#4', 'F#4', 'G#4',
    'A#4', 'C#5', 'D#5', 'F#5', 'G#5',
    'A#5', 'C#6', 'D#6', 'F#6', 'G#6',
    'A#6', 'C#7', 'D#7', 'F#7', 'G#7',
    'A#7'];

// Mapeamento para nomes de arquivos de som (ajuste conforme seus arquivos)
// Esta função ajudará a obter o nome correto do arquivo de som.
function getNoteFileName(noteName) {
    // Se a nota já tem 'b' (bemol), o nome do arquivo geralmente é direto.
    // Para sustenidos (#), vamos converter para 's'.
    // Ex: 'C4#' se tornará 'Cs4.wav', 'A0#' se tornará 'As0.wav'
    // Ex: 'Bb0' se tornará 'Bb0.wav'
    if (noteName.includes('#')) {
        return noteName.replace('#', 's') + '.wav';
    }
    return noteName + '.wav';
}