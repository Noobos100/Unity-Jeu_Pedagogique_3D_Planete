using System;
using System.Collections;
using System.Globalization;
using Script.Core.Quizz.Questions;
using Script.Services;
using UnityEngine;
using UnityEngine.Networking;

namespace Script.WebData
{
    
    [CreateAssetMenu(fileName = "DatabaseAccess", menuName = "ScriptableObjects/WebDatabaseAccess", order = 1)]
    public class WebDatabaseAccess : ScriptableObject, WebDatabaseAccessInterface
    {
        [SerializeField] private WebConnection _webDatabaseConnection;
        
        [Serializable]
        private class QuestionRandom
        {
            public int[] list;
        }
        
        public IEnumerator NewGame(string platform, string username)
        {
            string url = _webDatabaseConnection.GetHost() + "/new-game?plateforme=" + platform + "&username=" + username;
            Debug.Log(url);
        
            UnityWebRequest wwwNewGame = UnityWebRequest.Get(url);
            yield return wwwNewGame.SendWebRequest();
            if (wwwNewGame.error != null)
            {
                Debug.LogError(wwwNewGame.error);
            }
            else
            {
                Debug.Log("Page New Game Loaded");
                Debug.Log(wwwNewGame.downloadHandler.text);
            }
        }

        public IEnumerator AbortGame()
        {
            string url = _webDatabaseConnection.GetHost() + "/abort-on-going-game";
            Debug.Log(url);
        
            UnityWebRequest wwwAbortGame = UnityWebRequest.Get(url);
            yield return wwwAbortGame.SendWebRequest();
            if (wwwAbortGame.error != null)
            {
                Debug.LogError(wwwAbortGame.error);
            }
            else
            {
                Debug.Log("Page Abort Game Loaded");
                Debug.Log(wwwAbortGame.downloadHandler.text);
            }
        }

        public IEnumerator EndGame()
        {
            string url = _webDatabaseConnection.GetHost() + "/end-game";
            Debug.Log(url);
        
            UnityWebRequest wwwAbortGame = UnityWebRequest.Get(url);
            yield return wwwAbortGame.SendWebRequest();
            if (wwwAbortGame.error != null)
            {
                Debug.LogError(wwwAbortGame.error);
            }
            else
            {
                Debug.Log("Page End Game Loaded");
                Debug.Log(wwwAbortGame.downloadHandler.text);
            }
        }

        public IEnumerator AddInteraction(string type, float value, bool quizzStarted)
        {
            string strVarURLGet = "";
            strVarURLGet = "type=" + type + "&value=" + value.ToString().Replace(',', '.') + "&isEval=";
            if (quizzStarted)
            {
                strVarURLGet += "1";
            }
            else
            {
                strVarURLGet += "0";
            }
            string url = _webDatabaseConnection.GetHost() + "/add-interaction?" + strVarURLGet;
            Debug.Log(url);
        
            UnityWebRequest wwwInteract = UnityWebRequest.Get(url);
            yield return wwwInteract.SendWebRequest();
            if (wwwInteract.error != null)
            {
                Debug.LogError(wwwInteract.error);
            }
            else
            {
                Debug.Log("Page Interaction " + type + " Loaded");
                Debug.Log(wwwInteract.downloadHandler.text);
            }
        }

        public IEnumerator GetQuestion(int qid)
        {
            string strVarURLGet = "qid=" + qid;
            string url = _webDatabaseConnection.GetHost() + "/question?" + strVarURLGet;
            Debug.Log(url);

            UnityWebRequest wwwInteract = UnityWebRequest.Get(url);
            yield return wwwInteract.SendWebRequest();
            // Created var to store the html content shower will have to parse through
            string jsonString = "";
            // Checks if error
            if (wwwInteract.error != null)
            {
                Debug.LogError(wwwInteract.error);
                /* In case of emergency if its impossible to connect to the host since the start,
                 read the expected html page content for a known question and store the value for later used*/
                TextAsset questionTextAsset = Resources.Load<TextAsset>("WebEmergency/Questions/" + qid);
                jsonString = questionTextAsset.text;
            }
            else // No error, Web Page is loaded
            {
                Debug.Log(wwwInteract.downloadHandler.text); // le texte de la page
                jsonString = wwwInteract.downloadHandler.text;
            }
            
            // Init du JsonParser
            QuestionReadModel questionReadModel = JsonUtility.FromJson<QuestionReadModel>(jsonString);
            Debug.Log(questionReadModel);
            
            if (questionReadModel.Type.Equals("QCU")) // Question of type QCU with 4 choices
            {
                QuestionQCUReadModel qcuReadModel = JsonUtility.FromJson<QuestionQCUReadModel>(jsonString);
                Choice[] choices = new Choice[4];
                choices[0] = new Choice(qcuReadModel.Rep1, qcuReadModel.Rep1 == qcuReadModel.BonneRep) ;
                choices[1] = new Choice(qcuReadModel.Rep2, qcuReadModel.Rep2 == qcuReadModel.BonneRep) ;
                choices[2] = new Choice(qcuReadModel.Rep3, qcuReadModel.Rep3 == qcuReadModel.BonneRep) ;
                choices[3] = new Choice(qcuReadModel.Rep4, qcuReadModel.Rep4 == qcuReadModel.BonneRep) ;
                
                yield return new QuestionQCU(qcuReadModel.Num_Ques, QuestionType.Qcu, qcuReadModel.Enonce, choices);
            }
            
            else if (questionReadModel.Type.Equals("QUESINTERAC")) // Question of type Manipulation
            {
                QuestionManipulationReadModel manipulationReadModel = JsonUtility.FromJson<QuestionManipulationReadModel>(jsonString);

                yield return new QuestionManipulation(manipulationReadModel.Num_Ques, QuestionType.Manipulation, manipulationReadModel.Enonce, 
                    manipulationReadModel.BonneRepValeur_orbit, manipulationReadModel.BonneRepValeur_rotation, 
                    manipulationReadModel.Marge_Orbit, manipulationReadModel.Marge_Rotation);
            }
            
            else if (questionReadModel.Type.Equals("VRAIFAUX")) // Question of type TrueFalse
            {
                QuestionTrueFalseReadModel trueFalseReadModel = JsonUtility.FromJson<QuestionTrueFalseReadModel>(jsonString);
                Choice[] choices = {new Choice("Vrai", false), new Choice("Faux", false)};
                for (int i = 0; i < choices.Length; ++i)
                {
                    choices[i].correct = choices[i].value.Equals(trueFalseReadModel.BonneRep);
                }
                
                yield return new QuestionTrueFalse(trueFalseReadModel.Num_Ques, QuestionType.TrueFalse, trueFalseReadModel.Enonce, 
                    choices, trueFalseReadModel.Valeur_orbit, trueFalseReadModel.Valeur_rotation);
            }
            
        }

        public IEnumerator GetRandomQuestions(int nbQcu, int nbTrueFalse, int nbManipulation)
        {
            string strVarURLGet = "qcu=" + nbQcu + "&interaction=" + nbManipulation + "&vraifaux=" + nbTrueFalse;
            string url = _webDatabaseConnection.GetHost() + "/random-questions?" + strVarURLGet;
            Debug.Log(url);
        
            UnityWebRequest wwwInteract = UnityWebRequest.Get(url);
            yield return wwwInteract.SendWebRequest();
        
            string jsonString = "";
            // Checks if error
            if (wwwInteract.error != null)
            {
                Debug.LogError(wwwInteract.error);
                /* In case of emergency if its impossible to connect to the host since the start,
                 read the expected html page content for a known question and store the value for later used*/
                TextAsset questionTextAsset = Resources.Load<TextAsset>("WebEmergency/initQuizz");
                jsonString = questionTextAsset.text;
            }
            else // No error, Web Page is loaded
            {
                Debug.Log(wwwInteract.downloadHandler.text); // le texte de la page
                jsonString = wwwInteract.downloadHandler.text;
            }
        
            QuestionRandom questionRandom = JsonUtility.FromJson<QuestionRandom>(jsonString);
            Debug.Log(questionRandom);

            yield return questionRandom.list;

        }
        

        public IEnumerator AddUserAnswer(int qid, string dateStart, bool correct)
        {
            string strVarURLGet = "qid=" + qid + "&start=" + dateStart + "&correct=";
            if (correct)
            {
                strVarURLGet += "1";
            }
            else
            {
                strVarURLGet += "0";
            }
            string url = _webDatabaseConnection.GetHost() + "/question-answer?" + strVarURLGet;
            Debug.Log(url);
        
            UnityWebRequest wwwInteract = UnityWebRequest.Get(url);
            yield return wwwInteract.SendWebRequest();
            if (wwwInteract.error != null)
            {
                Debug.LogError(wwwInteract.error);
            }
            else // Pas d'erreur, la page est chargée
            {
                Debug.Log("Page Add User Answer Loaded");
                Debug.Log(wwwInteract.downloadHandler.text);
            }
        }
    }
}



