@extends('layouts.student')

@section('title', 'Take Quiz - ' . $quiz->quiz_title)

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ $quiz->quiz_title }}</h4>
                </div>
                <div class="card-body">
                    @if($quiz->description)
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>{{ $quiz->description }}
                        </div>
                    @endif

                    <!-- Quiz Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Total Questions:</strong> {{ $quiz->questions->count() }}</p>
                            @if($quiz->time_limit_minutes)
                                <p class="mb-1"><strong>Time Limit:</strong> {{ $quiz->time_limit_minutes }} minutes</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if($quiz->passing_score)
                                <p class="mb-1"><strong>Passing Score:</strong> {{ $quiz->passing_score }} points</p>
                            @endif
                            <p class="mb-1"><strong>Total Points:</strong> {{ $quiz->questions->sum('points') }}</p>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <strong><i class="bi bi-exclamation-triangle me-2"></i>Important:</strong>
                        <ul class="mb-0 mt-2">
                            <li>You can only take this quiz once</li>
                            <li>Answer all questions before submitting</li>
                            <li>Review your answers carefully</li>
                            @if($quiz->time_limit_minutes)
                                <li>The quiz will automatically submit after {{ $quiz->time_limit_minutes }} minutes</li>
                            @endif
                        </ul>
                    </div>

                    <form method="POST" action="{{ route('student.quiz.submit', $quiz->quiz_id) }}" id="quizForm">
                        @csrf

                        @foreach($quiz->questions as $index => $question)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h6 class="mb-3">
                                        <span class="badge bg-primary me-2">Q{{ $index + 1 }}</span>
                                        {{ $question->question_text }}
                                        <span class="badge bg-secondary ms-2">{{ $question->points }} {{ $question->points == 1 ? 'point' : 'points' }}</span>
                                    </h6>

                                    @if($question->question_type === 'multiple_choice')
                                        <!-- Multiple Choice -->
                                        @foreach($question->options as $optionIndex => $option)
                                            <div class="form-check mb-2">
                                                <input 
                                                    class="form-check-input" 
                                                    type="radio" 
                                                    name="answer_{{ $question->question_id }}" 
                                                    id="q{{ $question->question_id }}_opt{{ $optionIndex }}" 
                                                    value="{{ $option }}"
                                                    required
                                                >
                                                <label class="form-check-label" for="q{{ $question->question_id }}_opt{{ $optionIndex }}">
                                                    {{ $option }}
                                                </label>
                                            </div>
                                        @endforeach

                                    @elseif($question->question_type === 'true_false')
                                        <!-- True/False -->
                                        <div class="form-check mb-2">
                                            <input 
                                                class="form-check-input" 
                                                type="radio" 
                                                name="answer_{{ $question->question_id }}" 
                                                id="q{{ $question->question_id }}_true" 
                                                value="True"
                                                required
                                            >
                                            <label class="form-check-label" for="q{{ $question->question_id }}_true">
                                                True
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input 
                                                class="form-check-input" 
                                                type="radio" 
                                                name="answer_{{ $question->question_id }}" 
                                                id="q{{ $question->question_id }}_false" 
                                                value="False"
                                                required
                                            >
                                            <label class="form-check-label" for="q{{ $question->question_id }}_false">
                                                False
                                            </label>
                                        </div>

                                    @elseif($question->question_type === 'short_answer')
                                        <!-- Short Answer -->
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            name="answer_{{ $question->question_id }}" 
                                            placeholder="Type your answer here..."
                                            required
                                        >
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Are you sure you want to submit? You cannot change your answers after submission.')">
                                <i class="bi bi-check-circle me-2"></i>Submit Quiz
                            </button>
                            <a href="{{ route('student.appointment.view', $quiz->appointment_id) }}" class="btn btn-outline-secondary btn-lg">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if($quiz->time_limit_minutes)
    <!-- Timer -->
    <div class="position-fixed bottom-0 end-0 m-4">
        <div class="card border-warning shadow">
            <div class="card-body text-center">
                <h6 class="mb-2">Time Remaining</h6>
                <h3 class="mb-0 text-warning" id="timer">{{ $quiz->time_limit_minutes }}:00</h3>
            </div>
        </div>
    </div>
@endif
@endsection

@section('scripts')
@if($quiz->time_limit_minutes)
<script>
    // Timer
    let totalSeconds = {{ $quiz->time_limit_minutes * 60 }};
    const timerElement = document.getElementById('timer');
    const form = document.getElementById('quizForm');

    function updateTimer() {
        const minutes = Math.floor(totalSeconds / 60);
        const seconds = totalSeconds % 60;
        timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

        if (totalSeconds <= 0) {
            alert('Time is up! Your quiz will be submitted automatically.');
            form.submit();
            return;
        }

        if (totalSeconds <= 60) {
            timerElement.classList.add('text-danger');
            timerElement.classList.remove('text-warning');
        }

        totalSeconds--;
    }

    // Update timer every second
    updateTimer();
    const interval = setInterval(updateTimer, 1000);

    // Clear interval when form is submitted
    form.addEventListener('submit', function() {
        clearInterval(interval);
    });
</script>
@endif
@endsection