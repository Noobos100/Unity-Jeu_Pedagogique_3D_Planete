using System.Collections;

namespace Script.Services
{
    public interface WebDatabaseAccessInterface
    {

        public IEnumerator NewGame(string platform, string username);
        
        public IEnumerator AbortGame();
        
        public IEnumerator EndGame();
        
        public IEnumerator AddInteraction(string type, float value, bool quizzStarted);
        
        public IEnumerator GetQuestion(int qid);
        
        // public CoroutineWithData<Question> GetQuestionData(int qid);
        
        public IEnumerator GetRandomQuestions(int nbQcu, int nbTrueFalse, int nbManipulation);
        
        // public CoroutineWithData<int[]> GetRandomQuestionsData(int nbQcu, int nbTrueFalse, int nbManipulation);
        
        public IEnumerator AddUserAnswer(int qid, string dateStart, bool correct);

    }
}