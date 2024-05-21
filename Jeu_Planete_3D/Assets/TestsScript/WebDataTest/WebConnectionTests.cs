/*
using System.Collections;
using System.Collections.Generic;
using NUnit.Framework;
using UnityEngine;
using UnityEngine.TestTools;

public class WebConnectionTests
{
    private WebConnection webConnection;

    [SetUp]
    public void SetUp()
    {
        // Crée une nouvelle instance de WebConnection pour chaque test
        webConnection = new WebConnection("https://example.com");
    }

    [Test]
    public void GetHost_ReturnsCorrectHost()
    {
        // Arrange
        string expectedHost = "https://example.com";

        // Act
        string actualHost = webConnection.GetHost();

        // Assert
        Assert.AreEqual(expectedHost, actualHost);
    }

    [Test]
    public void SetHost_SetsNewHost()
    {
        // Arrange
        string newHost = "https://newhost.com";

        // Act
        webConnection.SetHost(newHost);
        string updatedHost = webConnection.GetHost();

        // Assert
        Assert.AreEqual(newHost, updatedHost);
    }
}
*/
