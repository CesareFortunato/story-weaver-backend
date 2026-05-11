import React, { useCallback, useEffect, useMemo, useState } from "react";
import { createRoot } from "react-dom/client";

import {
    ReactFlow,
    Background,
    Controls,
    MiniMap,
    Handle,
    Position,
    applyNodeChanges,
    applyEdgeChanges,
} from "@xyflow/react";

import "@xyflow/react/dist/style.css";

const rootElement = document.getElementById("story-graph-root");

function StoryNode({ data }) {
    const node = data.node;
    const choicesCount = node.choices?.length ?? 0;
    const isFinalNode = choicesCount === 0;

    return (
        <div
            style={{
                minWidth: "220px",
                maxWidth: "260px",
                borderRadius: "14px",
                overflow: "hidden",
                border: node.is_start
                    ? "2px solid #facc15"
                    : isFinalNode
                        ? "2px solid #ef4444"
                        : "1px solid #334155",
                background: "#020617",
                color: "#e5e7eb",
                boxShadow: "0 18px 40px rgba(0,0,0,0.35)",
            }}
        >
            <Handle type="target" position={Position.Top} />

            <div
                style={{
                    padding: "10px 12px",
                    background: node.is_start
                        ? "#713f12"
                        : isFinalNode
                            ? "#7f1d1d"
                            : "#1e293b",
                    fontWeight: "700",
                    fontSize: "14px",
                }}
            >
                {node.is_start ? "⭐ " : ""}
                {node.title || "Nodo senza titolo"}
            </div>

            <div style={{ padding: "12px" }}>
                <p
                    style={{
                        margin: 0,
                        fontSize: "12px",
                        lineHeight: "1.4",
                        color: "#cbd5e1",
                    }}
                >
                    {node.text?.length > 90
                        ? node.text.substring(0, 90) + "..."
                        : node.text}
                </p>

                <div
                    style={{
                        display: "flex",
                        gap: "6px",
                        marginTop: "10px",
                        flexWrap: "wrap",
                    }}
                >
                    <span
                        style={{
                            fontSize: "11px",
                            padding: "3px 8px",
                            borderRadius: "999px",
                            background: "#0f172a",
                            color: "#93c5fd",
                        }}
                    >
                        ID {node.id}
                    </span>

                    <span
                        style={{
                            fontSize: "11px",
                            padding: "3px 8px",
                            borderRadius: "999px",
                            background: "#0f172a",
                            color: isFinalNode ? "#fca5a5" : "#86efac",
                        }}
                    >
                        {isFinalNode ? "Finale" : `${choicesCount} scelte`}
                    </span>
                </div>
            </div>

            <Handle type="source" position={Position.Bottom} />
        </div>
    );
}

function StoryGraph() {
    const graphUrl = rootElement.dataset.graphUrl;
    const positionUrlTemplate = rootElement.dataset.positionUrlTemplate;
    const nodeUrlTemplate = rootElement.dataset.nodeUrlTemplate;

    const [nodes, setNodes] = useState([]);
    const [edges, setEdges] = useState([]);

    const nodeTypes = useMemo(() => ({
        storyNode: StoryNode,
    }), []);

    useEffect(() => {
        fetchGraph();
    }, []);

    async function fetchGraph() {
        try {
            const response = await fetch(graphUrl);
            const json = await response.json();

            const styledNodes = json.data.nodes.map((node) => ({
                ...node,
                type: "storyNode",
            }));

            setNodes(styledNodes);
            setEdges(json.data.edges);
        } catch (error) {
            console.error("Errore caricamento grafo:", error);
        }
    }

    const onNodesChange = useCallback((changes) => {
        setNodes((currentNodes) =>
            applyNodeChanges(changes, currentNodes)
        );
    }, []);

    const onEdgesChange = useCallback((changes) => {
        setEdges((currentEdges) =>
            applyEdgeChanges(changes, currentEdges)
        );
    }, []);

    function onNodeDoubleClick(event, node) {
        const url = nodeUrlTemplate.replace("__NODE_ID__", node.id);

        window.location.href = url;
    }

    async function onNodeDragStop(event, node) {
        const url = positionUrlTemplate.replace("__NODE_ID__", node.id);

        try {
            await fetch(url, {
                method: "PATCH",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({
                    position_x: Math.round(node.position.x),
                    position_y: Math.round(node.position.y),
                }),
            });
        } catch (error) {
            console.error("Errore salvataggio posizione nodo:", error);
        }
    }

    return (
        <ReactFlow
            nodes={nodes}
            edges={edges}
            nodeTypes={nodeTypes}
            onNodesChange={onNodesChange}
            onEdgesChange={onEdgesChange}
            onNodeDragStop={onNodeDragStop}
            onNodeDoubleClick={onNodeDoubleClick}
            fitView
        >
            <Background />
            <Controls />
            <MiniMap />
        </ReactFlow>
    );
}

if (rootElement) {
    createRoot(rootElement).render(<StoryGraph />);
}