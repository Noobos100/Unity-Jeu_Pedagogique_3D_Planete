/*
using System.Collections;
using System.Collections.Generic;
using NUnit.Framework;
using UnityEngine;
using UnityEngine.TestTools;

namespace Script.Services.Tests
{
    // Classe dérivée de MonoBehaviour spécifique aux tests
    public class CoroutineWithDataTestMonoBehaviour : MonoBehaviour { }

    public class CoroutineWithDataTests
    {
        private CoroutineWithDataTestMonoBehaviour testMonoBehaviour;

        [SetUp]
        public void SetUp()
        {
            // Initialisation des ressources nécessaires pour chaque test
            GameObject testObject = new GameObject();
            testMonoBehaviour = testObject.AddComponent<CoroutineWithDataTestMonoBehaviour>();
        }

        [TearDown]
        public void TearDown()
        {
            // Nettoyage des ressources après chaque test
            GameObject.Destroy(testMonoBehaviour.gameObject);
        }

        [UnityTest]
        public IEnumerator TestCoroutineWithData()
        {
            // Création de la coroutine à tester
            IEnumerator targetCoroutine = TestCoroutine();
            CoroutineWithData<int> coroutineWithData = new CoroutineWithData<int>(testMonoBehaviour, targetCoroutine);

            // Attendre la fin de la coroutine
            yield return coroutineWithData.coroutine;

            // Vérifier le résultat
            Assert.AreEqual(10, coroutineWithData.result);
        }

        // Coroutine de test renvoyant un entier après un certain temps
        private IEnumerator TestCoroutine()
        {
            yield return new WaitForSeconds(1.0f);
            yield return 10;
        }
    }
}
*/
