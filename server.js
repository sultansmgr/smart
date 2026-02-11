const express = require("express")
const fetch = (...args) => import('node-fetch').then(({default: fetch}) => fetch(...args))
const app = express()

app.get("/proxy", async (req, res) => {
  const target = req.query.url
  if (!target) return res.status(400).send("URL parametresi eksik")

  try {
    const response = await fetch(target, {
      headers: {
        "User-Agent": "Mozilla/5.0",
        "Referer": "https://www.kablowebtv.com/",
        "Origin": "https://www.kablowebtv.com"
      }
    })

    res.set("Access-Control-Allow-Origin", "*")
    res.set("Content-Type", response.headers.get("content-type"))
    response.body.pipe(res)

  } catch (e) {
    res.status(500).send("Hata: " + e.message)
  }
})

app.listen(process.env.PORT || 3000, () => {
  console.log("Proxy çalışıyor")
})
