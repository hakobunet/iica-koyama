"use client";

import { useState, useEffect } from "react";
import { useRouter, useParams } from "next/navigation";
import Link from "next/link";
import StudentDetail from "@/components/students/StudentDetail";

export default function StudentDetailPage() {
  const router = useRouter();
  const params = useParams(); // URLの {id} 部分を受け取る
  const [student, setStudent] = useState(null);
  const [userName, setUserName] = useState("");
  const [loading, setLoading] = useState(true);
  const [notFound, setNotFound] = useState(false);
  const [token, setToken] = useState(null); // ★ エラーの原因だった「token」の箱をここに追加しました！
  const [message, setMessage] = useState("");

  // ★ useEffect：ページが表示されたとき1回だけ実行される
  useEffect(() => {
    const savedToken = localStorage.getItem("token");

    // トークンがなければログインページに戻す
    if (!savedToken) {
      router.push("/login");
      return;
    }

    setToken(savedToken); // ★ 取得したトークンを状態(state)にしっかりと保存します
    setUserName(localStorage.getItem("userName") || "ユーザー");
    loadStudent(savedToken);
  }, []); // [] = 最初の1回だけ実行

  // 特定の生徒1人分のデータをAPIから取得する
  async function loadStudent(currentToken) {
    setLoading(true);
    setNotFound(false);
    setMessage("");

    try {
      // URLパラメータの params.id を使ってLaravel APIに問い合わせる
      const res = await fetch(`http://localhost:8000/api/students/${params.id}`, {
        headers: {
          "Accept": "application/json",
          "Authorization": `Bearer ${currentToken}`, // ★ トークンをヘッダーに付ける
        },
      });

      // 401 = トークンが無効 → ログインページに戻す
      if (res.status === 401) {
        router.push("/login");
        return;
      }

      // 404 = 生徒が見つからない
      if (res.status === 404) {
        setStudent(null);
        setNotFound(true);
        return;
      }

      const data = await res.json();
      setStudent(data.data); // Laravel API は { data: {...} } の形で返す
    } catch (error) {
      console.error("データの取得に失敗しました", error);
    } finally {
      setLoading(false);
    }
  }

  // ログアウト
  async function handleLogout() {
    const currentToken = localStorage.getItem("token");

    await fetch("http://localhost:8000/api/logout", {
      method: "POST",
      headers: {
        "Accept": "application/json",
        "Authorization": `Bearer ${currentToken}`,
      },
    });

    localStorage.removeItem("token");
    localStorage.removeItem("userName");
    router.push("/login");
  }

  // ★ トークンが準備できるか、ロードが終わるまで待つ
  if (!token || loading) {
    return (
      <div style={{ textAlign: "center", padding: "80px", color: "#888" }}>
        生徒データを読み込んでいます...
      </div>
    );
  }

  return (
    <div style={{ background: "#f4f6f8", minHeight: "100vh" }}>

      {/* ヘッダー（一覧画面と同じ共通の見た目） */}
      <header style={{
        background: "#1565C0", color: "white",
        padding: "16px 32px",
        display: "flex", alignItems: "center"
      }}>
        <h1 style={{ fontSize: "1.2em" }}>生徒管理アプリ（Next.js API版）</h1>
        <div style={{ marginLeft: "auto", display: "flex", alignItems: "center", gap: "12px" }}>
          <span style={{ fontSize: "0.9em" }}>● ログイン中：{userName}</span>
          <button
            onClick={handleLogout}
            style={{ background: "#D32F2F", color: "white", border: "none", borderRadius: "6px", padding: "6px 14px", cursor: "pointer" }}
          >
            ログアウト
          </button>
        </div>
      </header>

      {/* メインコンテンツ */}
      <main style={{ padding: "32px", maxWidth: "640px", margin: "0 auto", display: "grid", gap: "20px" }}>
        
        <Link href="/students" style={{ color: "#1565C0", fontWeight: "bold", textDecoration: "none" }}>
          ← 一覧に戻る
        </Link>

        {message ? (
          <div
            style={{
              borderRadius: 18,
              padding: "0.9rem 1rem",
              background: "rgba(255, 255, 255, 0.82)",
              border: "1px solid rgba(22, 61, 122, 0.08)",
              color: "#163D7A",
              fontWeight: 700,
            }}
          >
            {message}
          </div>
        ) : null}

        {/* ★ 生徒が見つからない、またはデータが空のときの表示 */}
        {notFound || !student ? (
          <div style={{
            borderRadius: "8px",
            padding: "40px 24px",
            textAlign: "center",
            background: "white",
            border: "1px solid #ddd",
          }}>
            <p style={{ margin: 0, color: "#888" }}>生徒が見つかりません</p>
          </div>
        ) : (
          /* ★ 正しく token を子コンポーネントに渡せるようになりました */
            <StudentDetail
                student={student}
                token={token}
                onUpdated={(updated) => {
                    setStudent(updated);
                    setMessage("生徒情報を更新しました");
                }}
                />
            )}
      </main>

    </div>
  );
}