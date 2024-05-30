using System;
using System.Collections;
using Script.Services;
using TMPro;
using UnityEngine;
using UnityEngine.SceneManagement;
using UnityEngine.UI;

namespace Script.Controllers
{
    public class GameController: MonoBehaviour
    {

        [SerializeField] private GameService _gameService;
        [SerializeField] private string sceneHomeName;
        [SerializeField] private string sceneGameName;
        [SerializeField] private string platform;

        [SerializeField] TMP_InputField usernameInput;
        [SerializeField] TextMeshProUGUI usernameError;

        WWWForm form;

        private void Start()
        {
            DisplaysAction();
        }

        private void Update()
        {
            DisplaysAction();
        }

        public IEnumerator StartGameAction()
        {
            string username = usernameInput.text.Trim();

            if (string.IsNullOrEmpty(username))
            {
                usernameError.text = "Veuillez Saisir un nom d'utilisateur";
            } else
            {
                usernameError.text = "";
                yield return StartCoroutine(_gameService.StartGame(platform, username));
                SceneManager.LoadScene(sceneGameName);
            }            
        }

        public void StartGame()
        {
            StartCoroutine(StartGameAction());
        }

        public IEnumerator EndGameAction()
        {
            yield return StartCoroutine(_gameService.EndGame());
            SceneManager.LoadScene(sceneHomeName);
        }
        
        public void EndGame()
        {
            StartCoroutine(EndGameAction());
        }

        public IEnumerator HomeAction()
        {
            _gameService.Resume();
            yield return StartCoroutine(_gameService.AbortGame());
            SceneManager.LoadScene(sceneHomeName);
        }
        
        public void Home()
        {
            StartCoroutine(HomeAction());
        }

        public void Pause()
        {
            _gameService.Pause();
        }

        public void Resume()
        {
            _gameService.Resume();
        }

        public void DisplaysAction()
        {
            _gameService.Displays();
        }
        
    }
}