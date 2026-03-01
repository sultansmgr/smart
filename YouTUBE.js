export default {
  async fetch(request) {
    try {
      const url = new URL(request.url);
      const liveID = url.searchParams.get("ID");

      if (!liveID) {
        return new Response("ID parametresi gerekli", { status: 400 });
      }

      const firstResponse = await fetch("https://ytdlp.online/", {
        method: "GET",
        headers: {
          "User-Agent": "Mozilla/5.0 (SMART-TV; LINUX; Tizen 6.0)",
        },
      });

      const setCookie = firstResponse.headers.get("set-cookie") || "";
      const sessionMatch = setCookie.match(/session=([^;]+)/);

      if (!sessionMatch) {
        return new Response("Session alınamadı", { status: 500 });
      }

      const token = sessionMatch[1];

      const streamURL =
        `https://ytdlp.online/stream?command=--get-url https://www.youtube.com/channel/${liveID}/live`;

      const secondResponse = await fetch(streamURL, {
        method: "GET",
        headers: {
          "User-Agent": "Mozilla/5.0 (SMART-TV; LINUX; Tizen 6.0)",
          "Accept": "text/event-stream",
          "Referer": "https://ytdlp.online/",
          "Cookie": `session=${token}`,
        },
      });

      const text = await secondResponse.text();

      const manifestMatch = text.match(/data:\s*(https:\/\/manifest\.googlevideo\.com[^\s]+)/);

      if (!manifestMatch) {
        return new Response("Manifest link bulunamadı", { status: 500 });
      }

      const finalLink = manifestMatch[1].trim();

      return Response.redirect(finalLink, 302);

    } catch (err) {
      return new Response("Hata: " + err.message, { status: 500 });
    }
  }
};