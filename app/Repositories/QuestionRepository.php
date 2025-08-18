<?php 

namespace App\Repositories;

use App\Models\Question;
use App\Repositories\Contracts\QuestionRepositoryInterface;
use Illuminate\Support\Collection;

class QuestionRepository implements QuestionRepositoryInterface
{
    public function findById(int $id): ?Question
    {
        return Question::with(['answerChoices'])->find($id);
    }

    public function getForTryout(int $tryoutId, bool $randomize = false): Collection
    {
        $query = Question::forTryout($tryoutId)
            ->withChoices();

        if ($randomize) {
            $query->inRandomOrder();
        }

        return $query->get();
    }

    public function getWithChoices(int $id): ?Question
    {
        return Question::with(['answerChoices'])->find($id);
    }

    public function search(string $query): Collection
    {
        return Question::where('isi_soal', 'LIKE', "%{$query}%")
            ->orWhere('pembahasan', 'LIKE', "%{$query}%")
            ->withChoices()
            ->get();
    }

    public function create(array $data): Question
    {
        return Question::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return Question::where('soal_id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return Question::destroy($id);
    }

    public function attachToTryout(int $questionId, int $tryoutId): bool
    {
        try {
            $question = Question::find($questionId);
            $question->tryouts()->attach($tryoutId);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function detachFromTryout(int $questionId, int $tryoutId): bool
    {
        try {
            $question = Question::find($questionId);
            $question->tryouts()->detach($tryoutId);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}