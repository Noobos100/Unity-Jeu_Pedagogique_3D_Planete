using System.Collections;
using Script.WebData;
using Unity.VisualScripting;
using UnityEngine;

namespace Script.Services
{
    public class GameService : MonoBehaviour
    {

        [SerializeReference] private WebDatabaseAccess linkWeb;
        
        [SerializeField] private GameObject pauseMenu;
        [SerializeField] private InformationDisplayer[] displayers;

        public IEnumerator StartGame(string platform, string username)
        {
            return linkWeb.NewGame(platform, username);
        }
        
        public IEnumerator AbortGame()
        {
            return linkWeb.AbortGame();
        }
        
        public IEnumerator EndGame()
        {
            return linkWeb.EndGame();
        }

        public void Pause()
        {
            pauseMenu.SetActive(true);
            Time.timeScale = 0;
        }

        public void Resume()
        {
            pauseMenu.SetActive(false);
            Time.timeScale = 1;
        }

        public void Displays()
        {
            foreach (InformationDisplayer displayed in displayers)
            {
                displayed.Display();
            }
        }

    }
}