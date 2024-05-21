/*
using System.Collections;
using System.Collections.Generic;
using NUnit.Framework;
using UnityEngine;
using UnityEngine.TestTools;


public class EllipseTests
{
    [Test]
    public void Evaluate_Returns_NotCorrectCoordinates()
    {
        // Arrange
        Ellipse ellipse = new Ellipse(3f, 5f);
        float t = 0.5f;
        Vector2 expectedCoordinates = new Vector2(0f, 5f);

        // Act
        Vector2 actualCoordinates = ellipse.Evaluate(t);

        // Assert
        Assert.AreNotEqual(expectedCoordinates, actualCoordinates);
    }

    [Test]
    public void FindProgressUsingX_Returns_CorrectProgress()
    {
        // Arrange
        Ellipse ellipse = new Ellipse(3f, 5f);
        float x = 0f;
        float y = 5f;
        float expectedProgress = 0.25f;

        // Act
        float actualProgress = ellipse.FindProgressUsingX(x, y);

        // Assert
        Assert.AreEqual(expectedProgress, actualProgress);
    }

    [Test]
    public void FindProgressUsingY_Returns_CorrectProgress()
    {
        // Arrange
        Ellipse ellipse = new Ellipse(3f, 5f);
        float x = 0f;
        float y = 5f;
        float expectedProgress = 0.25f;

        // Act
        float actualProgress = ellipse.FindProgressUsingY(x, y);

        // Assert
        Assert.AreEqual(expectedProgress, actualProgress);
    }

    [Test]
    public void FindProgress_Returns_CorrectProgress()
    {
        // Arrange
        Ellipse ellipse = new Ellipse(3f, 5f);
        float x = 0f;
        float y = 5f;
        float expectedProgress = 0.25f;

        // Act
        float actualProgress = ellipse.FindProgress(x, y);

        // Assert
        Assert.AreEqual(expectedProgress, actualProgress);
    }
}
*/

