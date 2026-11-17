import { redirect } from "next/navigation";

// / にアクセスしたら /login に自動で飛ばす
export default function Home() {
  redirect("/login");
}